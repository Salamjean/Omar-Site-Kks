@extends('user.layouts.templates')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsUsers/pages.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<h1 class="text-center mt-4" style="background-color: black; color:white">Les accessoires publiés</h1>
<div class="products-grid-container">
    @forelse($accessorys as $accessory)
        <div class="product-item">
            <div class="image-container">
                <img src="{{ asset('storage/' . $accessory->main_image) }}"
                     alt="{{ $accessory->name }}"
                     class="main-image">
                <img src="{{ asset('storage/' . $accessory->hover_image) }}"
                     alt="{{ $accessory->name }} - Back"
                     class="hover-image">
                <div class="quick-view">
                    <button type="button" class="btn-commander"
                            data-id="{{ $accessory->id }}"
                            data-name="{{ $accessory->name }}"
                            data-description="{{ $accessory->description }}"
                            data-price="{{ $accessory->price }}"
                            data-image="{{ asset('storage/' . $accessory->main_image) }}"
                            data-stock="{{ $accessory->nombre }}"
                            style="background: none; border: none; color: white; cursor: pointer;">
                        Commander
                    </button>
                </div>
            </div>
            <div class="product-details">
                <h3 class="product-name">{{ $accessory->name }}</h3>
                <div style="display: flex; justify-content:space-around">
                    <p class="product-price">Stock : {{ $accessory->nombre }}</p>
                    <p class="product-price">Prix : {{ number_format($accessory->price) }} Fcfa</p>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center" style="grid-column: 1 / -1;">Aucun accessoire disponible pour le moment.</p>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

                    // Afficher un loader pendant la requête
                    Swal.showLoading();

                    fetch(`{{ route('commandes.store', '') }}/${productId}`, {
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
                            // Mise à jour dynamique du stock et rechargement de la page
                            // const stockElement = document.querySelector(`[data-id="${productId}"] .product-price`);
                            // if (stockElement) {
                            //     stockElement.textContent = `Stock : ${data.stock_remaining}`;
                            // }
                            window.location.reload(); // Recharger la page après succès
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