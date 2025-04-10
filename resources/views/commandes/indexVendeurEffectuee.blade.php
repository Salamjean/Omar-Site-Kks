@extends('admin.vendeur.layouts.template')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4 text-center">Listes des articles livrés</h4>

      <div class="card">
        <div class="d-flex justify-content-between">
            <h5 class="card-header">Commandes effectuées</h5>
            <div class="pagination mt-3">
                {{ $commandes->links('partials.custom_pagination') }}
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-dark">
                <thead>
                  <tr style="text-align: center;">
                    <th>Image de face</th>
                    <th>Catégorie</th>
                    <th>Nom du article</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Montant total</th>
                    <th>Etat</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  @forelse ($commandes as $commande)
                  <tr style="text-align: center;">
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
                    <td>{{ $commande->quantity }}</td>
                    <td>{{ number_format($commande->total_price) }} Fcfa</td>
                    <td>{{ $commande->status }}</td>
                   
                  </tr>
                  @empty
                      <tr>
                          <td colspan="7" style="text-align: center;">Aucun article ajouté</td>
                      </tr>
                  @endforelse
                </tbody>
              </table>
        </div>
      </div>

      <!-- Modal -->
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
@endsection

<script>
    function showImage(imageElement) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageElement.src;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }
</script>