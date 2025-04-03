@extends('user.layouts.templates')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsUsers/pages.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assetsUsers/pages/new.js') }}"></script>

<h1 class="text-center mt-4" style="background-color: black; color:white">Les nouveautés (publiés dans les 2 derniers jours)</h1>

<!-- Formulaire de filtrage -->
<form method="GET" action="{{ url()->current() }}" id="filter-form">
    <div class="filter-container" style="display: flex; justify-content: center; gap: 20px; margin: 20px 0;">
        @foreach($categories as $category)
            <div class="form-check" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="categories[]" 
                       id="category-{{ $loop->index }}" 
                       value="{{ $category }}"
                       @if(in_array($category, request()->input('categories', []))) checked @endif
                       style="width: 20px; height: 20px; cursor: pointer; margin-bottom:8px">
                <label for="category-{{ $loop->index }}" style="cursor: pointer; font-size:20px; font-weight:bold">
                    {{ $category }}
                </label>
            </div>
        @endforeach
    </div>
</form>

<div class="products-grid-container">
    @forelse($newItems as $newItem)
        <div class="product-item">
            <h4 class="text-center text-black">{{ $newItem->categorie }}</h4>
            <div class="image-container">
                <img src="{{ asset('storage/' . $newItem->main_image) }}"
                     alt="{{ $newItem->name }}"
                     class="main-image">
                <img src="{{ asset('storage/' . $newItem->hover_image) }}"
                     alt="{{ $newItem->name }} - Back"
                     class="hover-image">
                <div class="quick-view">
                    <button type="button" class="btn-commander"
                            data-id="{{ $newItem->id }}"
                            data-name="{{ $newItem->name }}"
                            data-description="{{ $newItem->description }}"
                            data-price="{{ $newItem->price }}"
                            data-image="{{ asset('storage/' . $newItem->main_image) }}"
                            data-stock="{{ $newItem->nombre }}"
                            style="background: none; border: none; color: white; cursor: pointer;">
                        Commander
                    </button>
                </div>
            </div>
            <div class="product-details">
                <h3 class="product-name">{{ $newItem->name }}</h3>
                <div style="display: flex; justify-content:space-around">
                    <p class="product-price">Stock : {{ $newItem->nombre }}</p>
                    <p class="product-price">Prix : {{ number_format($newItem->price) }} Fcfa</p>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center" style="grid-column: 1 / -1;">Aucun article publié récemment.</p>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrage automatique quand une checkbox est cliquée
    document.querySelectorAll('input[name="categories[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });

    // Gestion du clic sur le bouton Commander
    document.querySelectorAll('.btn-commander').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productDescription = this.getAttribute('data-description');
            const productPrice = parseFloat(this.getAttribute('data-price'));
            const productImage = this.getAttribute('data-image');
            const productStock = parseInt(this.getAttribute('data-stock'));

            Swal.fire({
                title: 'Commander ' + productName,
                html: `
                    <div style="text-align:center">
                        <img src="${productImage}" alt="${productName}" style="max-height:200px;margin:10px auto">
                        <div style="text-align:center;margin:15px 0">
                            <p><strong>Nom :</strong> ${productName}</p>
                            <p><strong>Description :</strong> ${productDescription}</p>
                            <p><strong>Prix unitaire :</strong> ${productPrice.toLocaleString()} Fcfa</p>
                            <p><strong>Stock disponible :</strong> <span id="current-stock">${productStock}</span></p>
                        </div>
                        <div class="form-group">
                            <label for="swal-quantity"><strong>Quantité :</strong></label>
                            <input type="number" id="swal-quantity"
                                   class="swal2-input"
                                   min="1" max="${productStock}"
                                   value="1" required>
                        </div>
                        <div id="total-price" style="margin:15px 0;font-weight:bold">
                            Total: ${productPrice.toLocaleString()} Fcfa
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Valider la commande',
                cancelButtonText: 'Annuler',
                focusConfirm: false,
                didOpen: () => {
                    const quantityInput = document.getElementById('swal-quantity');
                    const totalElement = document.getElementById('total-price');

                    quantityInput.addEventListener('input', function() {
                        const quantity = parseInt(this.value) || 0;
                        const total = quantity * productPrice;
                        totalElement.textContent = `Total: ${total.toLocaleString()} Fcfa`;
                    });
                },
                preConfirm: () => {
                    const quantity = parseInt(document.getElementById('swal-quantity').value);

                    if (isNaN(quantity)) {
                        Swal.showValidationMessage('Veuillez entrer une quantité valide');
                        return false;
                    }
                    if (quantity < 1) {
                        Swal.showValidationMessage('La quantité doit être au moins 1');
                        return false;
                    }
                    if (quantity > productStock) {
                        Swal.showValidationMessage(`Stock insuffisant. Maximum disponible: ${productStock}`);
                        return false;
                    }

                    return { quantity: quantity };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const quantity = result.value.quantity;
                    const commandeUrl = '{{ route("commandes.store", ":id") }}'.replace(':id', productId);

                    // Afficher un loader pendant la requête
                    Swal.showLoading();

                    fetch(commandeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || 'Erreur serveur');
                        }
                        return data;
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès !',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: error.message || 'Une erreur est survenue',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    });
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Succès !',
    text: '{{ session('success') }}',
    confirmButtonText: 'OK'
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Erreur !',
    text: '{{ session('error') }}',
    confirmButtonText: 'Compris'
});
</script>
@endif
@endsection