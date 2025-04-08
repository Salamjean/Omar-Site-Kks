@extends('pages.layouts.templates')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsUsers/pages.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<h1 class="text-center mt-4" style="background-color: black; color:white">Les chaussures publiées</h1>
<div class="products-grid-container">
    @forelse($shoes as $shoe)
        <div class="product-item">
            <div class="image-container">
                <img src="{{ asset('storage/' . $shoe->main_image) }}"
                     alt="{{ $shoe->name }}"
                     class="main-image">
                <img src="{{ asset('storage/' . $shoe->hover_image) }}"
                     alt="{{ $shoe->name }} - Back"
                     class="hover-image">
                <div class="quick-view">
                    <a href="{{ route('login') }}">
                        <button type="button" class="btn-commander">
                            Commander
                        </button>
                    </a>
                </div>
            </div>
            <div class="product-details">
                <h3 class="product-name">{{ $shoe->name }}</h3>
                <div style="display: flex; justify-content:space-around">
                    <p class="product-price">Stock : {{ $shoe->nombre }}</p>
                    <p class="product-price">Prix : {{ number_format($shoe->price) }} Fcfa</p>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center" style="grid-column: 1 / -1;">Aucune chaussure disponible pour le moment.</p>
    @endforelse
</div>

@endsection