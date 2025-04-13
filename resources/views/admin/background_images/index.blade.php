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
        title: 'Suppression!',
        html: `
            <div style="display:flex; flex-direction:column; align-items:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#d33" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                <p style="margin-top:15px;">{{ session('error') }}</p>
            </div>
        `,
        showConfirmButton: true,
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card mb-4"> {{-- Card pour le formulaire d'upload, avec un peu de margin bottom --}}
                    <div class="card-header">
                        <h1 class="mb-0 text-center">Articles en tendance</h1> {{-- Titre dans le header de la card --}}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('background_images.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Télécharger l'image --}}
                            <div class="mb-3"> {{-- Utilisation de mb-3 pour l'espacement --}}
                                <label for="image" class="form-label">Télécharger l'image :</label> {{-- form-label pour Bootstrap --}}
                                <input type="file" name="image" id="image" class="form-control" required> {{-- form-control pour Bootstrap --}}
                                @error('image')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div> {{-- alert-danger pour Bootstrap pour les erreurs --}}
                                @enderror
                            </div>

                            <div class="d-grid gap-2"> {{-- d-grid pour que le bouton prenne toute la largeur --}}
                                <button type="submit" class="btn btn-primary"> {{-- btn-primary pour le style Bootstrap --}}
                                    <i class="fas fa-upload"></i> Télécharger
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="mb-4"> {{-- hr avec margin bottom pour espacement --}}

                <div class="card"> {{-- Card pour la section des images existantes --}}
                    <div class="card-header">
                        <h2 class="mb-0">Images existantes</h2> {{-- Titre dans le header de la card --}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($images as $image)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100"> {{-- Card pour chaque image, h-100 pour que toutes les cartes aient la même hauteur --}}
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="Background Image"> {{-- card-img-top pour l'image en haut de la card --}}
                                        <div class="card-body d-flex justify-content-center align-items-center"> {{-- Centrage du bouton dans le card-body --}}
                                            <form action="{{ route('background_images.destroy', $image->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"> {{-- btn-danger pour bouton rouge de suppression --}}
                                                    <i class="fas fa-trash-alt"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection