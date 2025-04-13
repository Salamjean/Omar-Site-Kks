@extends('admin.layouts.template')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      <h4 class="fw-bold py-3 mb-4 text-center">Listes de toutes les commandes payées</h4>

      <div class="card">
        <div class="d-flex justify-content-between">
            <h5 class="card-header">Commandes </h5>
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
                <th colspan="2">Actions</th>
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
                <td>{{ $commande->status }} 
                    <a href="#" onclick="showStatusModal({{ $commande->id }}, '{{ $commande->status }}')">
                        <i class="fas fa-edit me-1"></i>
                    </a>
                </td>
               
                <td>
                  <div class="btn-group gap-2" role="group">
                    <a href="{{ route('commandes.validate', $commande->id) }}">
                        <button type="button" class="btn btn-sm btn-outline-primary">
                          <i class="fas fa-square-check"></i> Valider
                        </button>
                      </a>
                      <a href="{{ route('commandes.cancel', $commande->id) }}">
                        <button type="button" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Réfuser
                        </button>
                    </a>
                  </div>
                </td>
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

<!-- Modal pour modifier le statut -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modifier l'état de la commande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateStatusForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label for="status" class="form-label">Donnez le nouvel état de la commande </label>
            <select class="form-select" id="status" name="status" required>
              <option value="En attente">En attente</option>
              <option value="En cours">En cours</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Mêttre à jour</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </form>
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

    function showStatusModal(commandeId, currentStatus) {
    const form = document.getElementById('updateStatusForm');
    form.action = "{{ route('commandes.updateStatus', ':id') }}".replace(':id', commandeId);
    
    const statusSelect = document.getElementById('status');
    // Sélectionner la première option par défaut
    statusSelect.selectedIndex = 0;
    
    // Afficher le modal
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    statusModal.show();
}

// Gérer la soumission du formulaire avec AJAX
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const url = form.action;
    const formData = new FormData(form);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PUT',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        Swal.fire('Succès!', data.message || 'Statut mis à jour', 'success')
           .then(() => window.location.reload());
    })
    .catch(error => {
        Swal.fire('Erreur!', error.message || 'Une erreur est survenue', 'error');
        console.error('Error:', error);
    });
});

// Ajoutez ce code dans votre section script
document.querySelectorAll('.btn-outline-primary').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.closest('a').href;
        
        Swal.fire({
            title: 'Confirmer la validation',
            text: "Voulez-vous vraiment valider cette commande?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, valider!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});

// Ajoutez ce code dans votre section script
document.querySelectorAll('.btn-outline-danger').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.closest('a').href;
        
        Swal.fire({
            title: 'Confirmer le refus',
            text: "Voulez-vous vraiment refuser cette commande?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, refuser!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>