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
                    <th>Valider/Réfuse par:</th>
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
                      @if($commande->vendor)
                          {{ $commande->vendor->name }} {{ $commande->vendor->prenom }}
                      @else
                          Admin (Vous)
                      @endif
                  </td>
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
                          <td colspan="8" style="text-align: center;">Aucun article ajouté</td>
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