@extends('user.layouts.templates')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="content-wrapper">
    <h4 class="fw-bold py-3 mb-4 mt-4 text-center text-black">Mes commandes</h4>
    <div class="container-xxl flex-grow-1 container-p-y d-flex justify-content-center">
      <div class="card col-10 d-flex justify-content-center ">
        <div class="d-flex justify-content-between">
            <h5 class="card-header">Articles commandé</h5>
            <div class="pagination mt-3">
                {{ $commandes->links('partials.custom_pagination') }}
            </div>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table table-dark">
            <thead>
              <tr style="text-align: center; color:black; background-color:antiquewhite">
                <th>Image d'article</th>
                <th>Type d'article</th>
                <th>Nom du article</th>
                <th>Prix unitaire</th>
                <th>Quantité commandé</th>
                <th>Total à payer</th>
                <th>Statut</th>
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($commandes as $commande)
              <tr style="text-align: center;" data-commande-id="{{ $commande->id }}">
                <td>
                    <img src="{{ asset('storage/' . $commande->main_image) }}"
                         alt="{{ $commande->name }}"
                         width="50"
                         height="50"
                         style="object-fit: cover; cursor: pointer;"
                         class="rounded"
                         onclick="showImage(this)">
                </td>
                <td>{{ $commande->categorie }}</td>
                <td>{{ $commande->article_name }}</td>
                <td>{{ number_format($commande->unit_price) }} Fcfa</td>
                <td class="quantity-cell">{{ $commande->quantity }}</td>
                <td class="total-price-cell">{{ number_format($commande->total_price) }} Fcfa</td>
                <td>{{ $commande->status }}</td>
                <td>
                  <div class="btn-group" style="gap: 10px" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-modifier" 
                            data-commande-id="{{ $commande->id }}"
                            data-current-quantity="{{ $commande->quantity }}"
                            data-unit-price="{{ $commande->unit_price }}">
                      <i class="fas fa-edit me-1"></i> Modifier
                    </button>
                    <button type="button" class="btn btn-sm btn-success btn-payer" 
                            data-commande-id="{{ $commande->id }}"
                            data-amount="{{ $commande->total_price }}"
                            data-article-name="{{ $commande->article_name }}"
                            data-user-name="{{ Auth::user()->name }}"
                            data-user-email="{{ Auth::user()->email }}"
                            data-user-phone="{{ Auth::user()->phone ?? '' }}">
                      <i class="fas fa-credit-card me-1"></i> Payer
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-annuler" 
                            data-commande-id="{{ $commande->id }}"
                            data-quantity="{{ $commande->quantity }}"
                            data-article-id="{{ $commande->article_id }}">
                        <i class="fas fa-trash me-1"></i> Annuler
                    </button>
                  </div>
                </td>
              </tr>
              @empty
                  <tr>
                      <td colspan="8" style="text-align: center;">Aucune commande trouvée</td>
                  </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal pour l'image -->
      <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Vu de l'image</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
              <img id="modalImage" src="" alt="Image agrandie" class="img-fluid">
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- Chargement des scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.cinetpay.com/seamless/main.js"></script>
<script src="{{ asset('assets/cinetPay/cinetpay.js') }}"></script>

<script>
    function showImage(imageElement) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageElement.src;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les boutons de paiement
        if (typeof CinetPayHelper !== 'undefined') {
            CinetPayHelper.setupButtons();
        }

        // Gestion des modifications de quantité
        document.querySelectorAll('.btn-modifier').forEach(button => {
            button.addEventListener('click', function() {
                const commandeId = this.getAttribute('data-commande-id');
                const currentQuantity = parseInt(this.getAttribute('data-current-quantity'));
                const unitPrice = parseFloat(this.getAttribute('data-unit-price'));
                
                Swal.fire({
                    title: 'Modifier la quantité',
                    html: `
                        <div style="text-align:center">
                            <div style="text-align:left;margin:15px 0">
                                <p><strong>Quantité actuelle :</strong> ${currentQuantity}</p>
                                <p><strong>Prix unitaire :</strong> ${unitPrice.toLocaleString()} Fcfa</p>
                                <p><strong>Total actuel :</strong> ${(unitPrice * currentQuantity).toLocaleString()} Fcfa</p>
                            </div>
                            <div class="form-group">
                                <label for="swal-quantity"><strong>Nouvelle quantité :</strong></label>
                                <input type="number" id="swal-quantity" 
                                       class="swal2-input" 
                                       min="1" 
                                       value="${currentQuantity}" required>
                            </div>
                            <div id="new-total-price" style="margin:15px 0;font-weight:bold">
                                Nouveau total: ${(unitPrice * currentQuantity).toLocaleString()} Fcfa
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Valider',
                    cancelButtonText: 'Annuler',
                    focusConfirm: false,
                    didOpen: () => {
                        const quantityInput = document.getElementById('swal-quantity');
                        const totalElement = document.getElementById('new-total-price');
                        
                        quantityInput.addEventListener('input', function() {
                            const quantity = parseInt(this.value) || 0;
                            const total = quantity * unitPrice;
                            totalElement.textContent = `Nouveau total: ${total.toLocaleString()} Fcfa`;
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
                        
                        return { quantity: quantity };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newQuantity = result.value.quantity;
                        
                        Swal.showLoading();
                        
                        fetch(`{{ route('commandes.updateQuantity', ':id') }}`.replace(':id', commandeId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                quantity: newQuantity
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

        // Fonction pour annuler une commande
        document.querySelectorAll('.btn-annuler').forEach(button => {
            button.addEventListener('click', function() {
                const commandeId = this.getAttribute('data-commande-id');
                const quantity = this.getAttribute('data-quantity');
                const articleId = this.getAttribute('data-article-id');
                
                Swal.fire({
                    title: 'Confirmer l\'annulation',
                    text: "Êtes-vous sûr de vouloir annuler cette commande ? La quantité sera restituée en stock.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, annuler',
                    cancelButtonText: 'Non, garder'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.showLoading();
                        
                        fetch(`{{ route('commandes.annuler', ':id') }}`.replace(':id', commandeId), {
                              method: 'POST',
                              headers: {
                                  'Content-Type': 'application/json',
                                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                  'Accept': 'application/json'
                              },
                              body: JSON.stringify({
                                  quantity: quantity,
                                  article_id: articleId
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
                                const row = document.querySelector(`[data-commande-id="${commandeId}"]`);
                                if (row) {
                                    row.remove();
                                }
                                
                                if (document.querySelectorAll('tbody tr[data-commande-id]').length === 0) {
                                    document.querySelector('tbody').innerHTML = `
                                        <tr>
                                            <td colspan="8" style="text-align: center;">Aucune commande trouvée</td>
                                        </tr>
                                    `;
                                }
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: error.message || 'Une erreur est survenue lors de l\'annulation',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                });
            });
        });
    });
</script>
@endsection