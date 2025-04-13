@extends('admin.layouts.template')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Succès!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur de validation',
        html: `<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
</script>
@endif
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4 text-center">Listes des articles publiés</h4>

      <div class="card">
        <div class="d-flex justify-content-between">
          <h5 class="card-header">Articles</h5>
          <div class="pagination mt-3">
              {{ $articles->links('partials.custom_pagination') }}
          </div>
      </div>
        <div class="table-responsive text-nowrap">
          <table class="table table-dark">
            <thead>
              <tr style="text-align: center;">
                <th>Nom du article</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Catégorie</th>
                <th>Description</th>
                <th>Statut</th>
                <th>Image de face</th>
                <th>Image de dos</th>
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($articles as $article)
              <tr style="text-align: center;">
               
                <td>{{ $article->name }}</td>
                <td>{{ number_format($article->price) }} Fcfa</td>
                <td>{{ $article->nombre }}</td>
                <td>{{ $article->categorie }}</td>
                <td>{{ $article->description }}</td>
                <td>{{ $article->status }}</td>
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
                <td>
                  <div class="btn-group gap-2" role="group">
                    <a href="{{ route('article.edit', $article->id) }}">
                      <button type="button" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit me-1"></i> Modifier
                    </button>
                  </a>
                    <a href="{{ route('article.destroy', $article->id) }}">
                      <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i> Supprimer
                      </button>
                    </a>
                  </div>
                </td>
              </tr>
              @empty
                  <tr>
                      <td colspan="11" style="text-align: center;">Aucun article ajouté</td>
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