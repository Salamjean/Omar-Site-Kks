@extends('admin.vendeur.layouts.template')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4 text-center">Listes des articles des partenaires</h4>

      <div class="card">
        <div class="d-flex justify-content-between">
          <h5 class="card-header">Partenaires articles</h5>
          <div class="pagination mt-3">
              {{ $articles->links('partials.custom_pagination') }}
          </div>
      </div>
        <div class="table-responsive text-nowrap">
          <table class="table table-dark">
            <thead>
              <tr style="text-align: center;">
                <th>Nom du partenaire</th>
                <th>Nom du accessoire</th>
                <th>Prix unitaire</th>
                <th>Nombre</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Statut</th>
                <th>Montant initial</th>
                <th>Montant réçu</th>
                <th>Image de face</th>
                <th>Image de dos</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($articles as $article)
              <tr style="text-align: center;">
               
                <td>{{ $article->vendor->name }}</td>
                <td>{{ $article->name }}</td>
                <td>{{ number_format($article->price) }} Fcfa</td>
                <td>{{ $article->nombre }}</td>
                <td>{{ $article->categorie }}</td>
                <td>{{ $article->description }}</td>
                <td>{{ $article->status }}</td>
                <td>{{ $article->total }} Fcfa</td>
                <td>{{ $article->reduced }} Fcfa</td>
                <td>
                  <img src="{{ asset('storage/' . $article->main_image) }}"
                       alt="{{ $article->name }}"
                       width="50"
                       height="50"
                       style="object-fit: cover; cursor: pointer;"
                       class="rounded"
                       onclick="showImage(this)">
              </td>
              <td>
                <img src="{{ asset('storage/' . $article->hover_image) }}"
                     alt="{{ $article->name }}"
                     width="50"
                     height="50"
                     style="object-fit: cover; cursor: pointer;"
                     class="rounded"
                     onclick="showImage(this)">
            </td>
              </tr>
              @empty
                  <tr>
                      <td colspan="11" style="text-align: center;">Aucun accessoire ajouté</td>
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