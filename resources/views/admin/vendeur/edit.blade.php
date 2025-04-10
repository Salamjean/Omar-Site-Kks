@extends('admin.layouts.template')
@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h1 class="mb-0 text-center">Modifier un personnel</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vendor.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row"> 
                                {{-- Nom du personnel --}}
                                <div class="mb-3 col-md-6">
                                    <label for="product-name" class="form-label">Nom du personnel :</label>
                                    <input type="text" class="form-control" id="product-name" name="name" value="{{ old('name', $vendor->name) }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Prénom du personnel --}}
                                <div class="mb-3 col-md-6">
                                    <label for="prenom" class="form-label">Prénoms du personnel:</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom', $vendor->prenom) }}">
                                    @error('prenom')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 
                
                            <div class="row"> 
                                {{-- Email --}}
                                <div class="mb-3 col-6">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $vendor->email) }}">
                                    @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Contact --}}
                                <div class="mb-3 col-md-6">
                                    <label for="contact" class="form-label">Contact :</label>
                                    <input type="tel" class="form-control" id="contact" name="contact" value="{{ old('contact', $vendor->contact) }}">
                                    @error('contact')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 

                            <div class="row"> 
                                {{-- Date de naissance --}}
                                <div class="mb-3 col-md-6">
                                    <label for="dateNaiss" class="form-label">Date de naissance :</label>
                                    <input type="date" class="form-control" id="dateNaiss" name="dateNaiss" value="{{ old('dateNaiss', $vendor->dateNaiss) }}">
                                    @error('dateNaiss')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Commune --}}
                                <div class="mb-3 col-6">
                                    <label for="commune" class="form-label">Commune de résidence :</label>
                                    <input type="text" class="form-control" id="commune" name="commune" value="{{ old('commune', $vendor->commune) }}">
                                    @error('commune')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                {{-- Rôle --}}
                                <div class="mb-4 col-md-6"> 
                                    <label for="role" class="form-label text-center">Rôle</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="">Sélectionnez son rôle</option>
                                        <option value="Personnel" {{ old('role', $vendor->role) == 'Personnel' ? 'selected' : '' }}>Personnel</option>
                                        <option value="Fournisseur" {{ old('role', $vendor->role) == 'Fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                                    </select>
                                    @error('role')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection