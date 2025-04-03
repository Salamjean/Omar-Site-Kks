@extends('admin.layouts.template')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">
                        <h1 class="mb-0 text-center">Modifier les informations du vêtement</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('article.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row"> 
                                <div class="mb-3 col-md-6">
                                    <label for="product-name" class="form-label">Nom du vêtement :</label>
                                    <input type="text" class="form-control" id="product-name" name="product-name" value="{{ old('product-name', $article->name) }}">
                                    @error('product-name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="product-price" class="form-label">Prix du vêtement :</label>
                                    <input type="text" class="form-control" id="product-price" name="product-price" value="{{ old('product-price', $article->price) }}">
                                    @error('product-price')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 

                            <div class="row"> 
                                <div class="mb-3 col-md-6">
                                    <label for="nombre" class="form-label">Total en stock :</label>
                                    <input type="number" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $article->nombre) }}">
                                    @error('nombre')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="description" class="form-label">Description du vêtement :</label>
                                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $article->description) }}">
                                    @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 

                            <div class="row"> 
                                <div class="mb-3 col-6">
                                    <label for="main-image" class="form-label">Image de face :</label>
                                    <input type="file" class="form-control" id="main-image" name="main-image">
                                    @if($article->main_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$article->main_image) }}" width="100" class="img-thumbnail">
                                            <small>Image actuelle</small>
                                        </div>
                                    @endif
                                    @error('main-image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-6">
                                    <label for="hover-image" class="form-label">Image de dos :</label>
                                    <input type="file" class="form-control" id="hover-image" name="hover-image">
                                    @if($article->hover_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$article->hover_image) }}" width="100" class="img-thumbnail">
                                            <small>Image actuelle</small>
                                        </div>
                                    @endif
                                    @error('hover-image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="mb-3 col-md-6"> 
                                    <label for="categorie" class="form-label text-center">Catégorie</label>
                                    <select class="form-select" id="categorie" name="categorie" onchange="toggleAdditionalFields()">
                                        <option value="">Sélectionnez une catégorie</option>
                                        <option value="Vêtement" {{ old('categorie', $article->categorie) == 'Vêtement' ? 'selected' : '' }}>Vêtement</option>
                                        <option value="Chaussure" {{ old('categorie', $article->categorie) == 'Chaussure' ? 'selected' : '' }}>Chaussure</option>
                                        <option value="Accessoire" {{ old('categorie', $article->categorie) == 'Accessoire' ? 'selected' : '' }}>Accessoire</option>
                                    </select>
                                    @error('categorie')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section pour les types d'accessoires -->
                            <div class="row justify-content-center" id="accessoryTypeContainer" style="display: {{ old('categorie', $article->categorie) == 'Accessoire' ? 'flex' : 'none' }};">
                                <div class="mb-3 col-md-6"> 
                                    <label for="typeAccessoire" class="form-label text-center">Type de l'accessoire</label>
                                    <select class="form-select" id="typeAccessoire" name="typeAccessoire" onchange="toggleOtherAccessoryField()">
                                        <option value="">Sélectionnez le type</option>
                                        <option value="Montre">Montre</option>
                                        <option value="Bijou">Bijou</option>
                                        <option value="Ceinture">Ceinture</option>
                                        <option value="Chapeau">Chapeau</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                    @error('typeAccessoire')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Champ pour type d'accessoire personnalisé -->
                            <div class="row justify-content-center" id="otherAccessoryContainer" style="display: none;">
                                <div class="mb-3 col-md-6"> 
                                    <label for="other" class="form-label text-center">Précisez le type</label>
                                    <input type="text" class="form-control" id="other" name="other" placeholder="Entrez le type d'accessoire">
                                    @error('other')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAdditionalFields() {
            const category = document.getElementById('categorie').value;
            const accessoryContainer = document.getElementById('accessoryTypeContainer');
            const otherAccessoryContainer = document.getElementById('otherAccessoryContainer');
            
            // Masquer tous les champs supplémentaires
            accessoryContainer.style.display = 'none';
            otherAccessoryContainer.style.display = 'none';

            // Afficher les champs appropriés
            if (category === 'Accessoire') {
                accessoryContainer.style.display = 'flex';
                // Vérifier si "Autre" est sélectionné
                if (document.getElementById('typeAccessoire').value === 'Autre') {
                    otherAccessoryContainer.style.display = 'flex';
                }
            }
        }

        function toggleOtherAccessoryField() {
            const accessoryType = document.getElementById('typeAccessoire').value;
            const otherAccessoryContainer = document.getElementById('otherAccessoryContainer');
            
            if (accessoryType === 'Autre') {
                otherAccessoryContainer.style.display = 'flex';
            } else {
                otherAccessoryContainer.style.display = 'none';
            }
        }

        // Initialiser l'affichage au chargement
        document.addEventListener('DOMContentLoaded', function() {
            toggleAdditionalFields();
            
            // Pré-sélectionner le type d'accessoire si existant
            @if(old('categorie', $article->categorie) == 'Accessoire' && $article->typeAccessoire)
                document.getElementById('typeAccessoire').value = "{{ $article->typeAccessoire }}";
                if ("{{ $article->typeAccessoire }}" === 'Autre') {
                    document.getElementById('otherAccessoryContainer').style.display = 'flex';
                    document.getElementById('other').value = "{{ $article->other ?? '' }}";
                }
            @endif
        });
    </script>
@endsection