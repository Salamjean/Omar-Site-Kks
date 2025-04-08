@extends('fournisseur.layouts.template')
@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h1 class="mb-0 text-center">Ajouter un article</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('fournisseur.storeArticle') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row"> 
                                {{-- Nom du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Nom du article :</label>
                                    <input type="text" class="form-control" id="name" value="{{ old('name') }}" name="name">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Prix du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="price" class="form-label">Prix de l'article :</label>
                                    <input type="number" class="form-control" id="price" value="{{ old('price') }}" name="price" oninput="calculateTotal()">
                                    @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 

                            <div class="row"> 
                                {{-- nombre du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="nombre" class="form-label">Quantité :</label>
                                    <input type="number" class="form-control" id="nombre" value="{{ old('nombre') }}" name="nombre" oninput="calculateTotal()">
                                    @error('nombre')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- description du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="description" class="form-label">Description de l'article :</label>
                                    <input type="text" class="form-control" id="description" value="{{ old('description') }}" name="description">
                                    @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 

                            <div class="row">
                                {{-- Montant total --}}
                                <div class="mb-3 col-md-6">
                                    <label for="total" class="form-label">Montant total :</label>
                                    <input type="text" class="form-control" id="total" name="total" readonly>
                                </div>

                                {{-- Montant réduit de 10% --}}
                                <div class="mb-3 col-md-6">
                                    <label for="reduced" class="form-label">Montant réduit (10%) :</label>
                                    <input type="text" class="form-control" id="reduced" name="reduced" readonly>
                                </div>
                            </div>

                            <div class="row"> 
                                {{-- Image principale (Face du article) --}}
                                <div class="mb-3 col-6">
                                    <label for="main-image" class="form-label">Image de face :</label>
                                    <input type="file" class="form-control" id="main-image" value="{{ old('main_image') }}" name="main_image">
                                    @error('main_image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image secondaire (Arrière du article) --}}
                                <div class="mb-3 col-6">
                                    <label for="hover-image" class="form-label">Image de dos :</label>
                                    <input type="file" class="form-control" id="hover-image" value="{{ old('hover_image') }}" name="hover_image">
                                    @error('hover_image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                {{-- categorie du article --}}
                                <div class="mb-3 col-md-6"> 
                                    <label for="categorie" class="form-label text-center">Catégorie</label>
                                    <select class="form-select" id="categorie" name="categorie" onchange="toggleAccessoryType()">
                                        <option value="">Sélectionnez une catégorie</option>
                                        <option value="Vêtement">Vêtement</option>
                                        <option value="Chaussure">Chaussure</option>
                                        <option value="Accessoire">Accessoire</option>
                                    </select>
                                    @error('categorie')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- type accessoire du article (caché par défaut) --}}
                            <div class="row justify-content-center" id="accessoryTypeContainer" style="display: none;">
                                <div class="mb-3 col-md-6"> 
                                    <label for="typeAccessoire" class="form-label text-center">Type de l'accessoire</label>
                                    <select class="form-select" id="typeAccessoire" name="typeAccessoire" onchange="toggleOtherAccessoryField()">
                                        <option value="">Sélectionnez le type de l'accessoire</option>
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

                            {{-- Champ pour autre type d'accessoire (caché par défaut) --}}
                            <div class="row justify-content-center" id="otherAccessoryContainer" style="display: none;">
                                <div class="mb-3 col-md-6"> 
                                    <label for="other" class="form-label text-center">Précisez le type d'accessoire</label>
                                    <input type="text" class="form-control" id="other" name="other" placeholder="Entrez le type d'accessoire">
                                    @error('other')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter le vêtement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAccessoryType() {
            const categorySelect = document.getElementById('categorie');
            const accessoryTypeContainer = document.getElementById('accessoryTypeContainer');
            const otherAccessoryContainer = document.getElementById('otherAccessoryContainer');
            
            if (categorySelect.value === 'Accessoire') {
                accessoryTypeContainer.style.display = 'flex';
            } else {
                accessoryTypeContainer.style.display = 'none';
                otherAccessoryContainer.style.display = 'none';
            }
        }

        function toggleOtherAccessoryField() {
            const accessoryTypeSelect = document.getElementById('typeAccessoire');
            const otherAccessoryContainer = document.getElementById('otherAccessoryContainer');
            
            if (accessoryTypeSelect.value === 'Autre') {
                otherAccessoryContainer.style.display = 'flex';
            } else {
                otherAccessoryContainer.style.display = 'none';
            }
        }

        function calculateTotal() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseInt(document.getElementById('nombre').value) || 0;
            
            const total = price * quantity;
            const reduced = total * 0.9; // 10% de réduction
            
            document.getElementById('total').value = total.toFixed(0);
            document.getElementById('reduced').value = reduced.toFixed(0);
        }

        // Appeler les fonctions au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            toggleAccessoryType();
            toggleOtherAccessoryField();
            calculateTotal(); // Pour calculer les valeurs initiales si des valeurs par défaut existent
        });
    </script>
@endsection