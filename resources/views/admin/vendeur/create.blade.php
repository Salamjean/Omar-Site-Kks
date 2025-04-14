@extends('admin.layouts.template')

@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h1 class="mb-0 text-center">Ajouter un personnel</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('vendor.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row"> 
                                {{-- Nom du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="product-name" class="form-label">Nom du personnel :</label>
                                    <input type="text" class="form-control" id="product-name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Prix du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="prenom" class="form-label">Prénoms du personnel:</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}">
                                    @error('prenom')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 
                
                            <div class="row"> 
                                {{-- nombre du article --}}
                                <div class="mb-3 col-6">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- description du article --}}
                                <div class="mb-3 col-md-6">
                                    <label for="contact" class="form-label">Contact :</label>
                                    <input type="tel" class="form-control" id="contact" name="contact" value="{{ old('contact') }}">
                                    @error('contact')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> 

                            <div class="row"> 
                                {{-- Image principale (Face du article) --}}
                                <div class="mb-3 col-md-6">
                                    <label for="dateNaiss" class="form-label">Date de naissance :</label>
                                    <input type="date" class="form-control" id="dateNaiss" name="dateNaiss" value="{{ old('dateNaiss') }}">
                                    @error('dateNaiss')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image secondaire (Arrière du article) --}}
                                <div class="mb-3 col-6">
                                    <label for="commune" class="form-label">Commune de résidence :</label>
                                    <input type="text" class="form-control" id="commune" name="commune" value="{{ old('commune') }}">
                                    @error('commune')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                {{-- categorie du article --}}
                                <div class="mb-4 col-md-6"> 
                                    <label for="role" class="form-label text-center">Rôle</label>
                                    <select class="form-select" id="role" name="role" >
                                        <option value="">Sélectionnez son rôle</option>
                                        <option value="Personnel">Personnel</option>
                                        <option value="Partenaire">Partenaire</option>
                                    </select>
                                    @error('role')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Ajouter le personnel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection