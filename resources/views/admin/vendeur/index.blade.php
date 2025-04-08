@extends('admin.layouts.template')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4 text-center">Listes des personnels</h4>

      <div class="card">
        <div class="d-flex justify-content-between">
          <h5 class="card-header">Personnels</h5>
          <div class="pagination mt-3">
              {{ $vendors->links('partials.custom_pagination') }}
          </div>
      </div>
        <div class="table-responsive text-nowrap">
          <table class="table table-dark">
            <thead>
              <tr style="text-align: center;">
                <th>Nom et prénoms</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Date de naissance</th>
                <th>Commune de residence</th>
                <th>Role</th>
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @forelse ($vendors as $vendor)
              <tr style="text-align: center;">
               
                <td>{{ $vendor->name }} {{ $vendor->prenom }}</td>
                <td>{{ $vendor->email }}</td>
                <td>{{ $vendor->contact }}</td>
                <td>{{ $vendor->dateNaiss }}</td>
                <td>{{ $vendor->commune }}</td>
                <td>{{ $vendor->role }}</td>
                <td>
                  <div class="btn-group gap-2" role="group">
                    <a href="#">
                      <button type="button" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit me-1"></i> Modifier
                    </button>
                  </a>
                    <a href="#">
                      <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash me-1"></i> Supprimer
                      </button>
                    </a>
                  </div>
                </td>
              </tr>
              @empty
                  <tr>
                      <td colspan="7" style="text-align: center;">Aucun personnel ajouté</td>
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