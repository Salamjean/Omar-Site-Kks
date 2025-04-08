@extends('pages.layouts.templates')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsUsers/pages.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<h1 class="text-center mt-4" style="background-color: black; color:white">Les vêtements publiés</h1>
<div class="products-grid-container">
    @forelse($clothes as $clothe)
        <div class="product-item">
            <div class="image-container">
                <img src="{{ asset('storage/' . $clothe->main_image) }}"
                     alt="{{ $clothe->name }}"
                     class="main-image">
                <img src="{{ asset('storage/' . $clothe->hover_image) }}"
                     alt="{{ $clothe->name }} - Back"
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
                <h3 class="product-name">{{ $clothe->name }}</h3>
                <div style="display: flex; justify-content:space-around">
                    <p class="product-price">Stock : {{ $clothe->nombre }}</p>
                    <p class="product-price">Prix : {{ number_format($clothe->price) }} Fcfa</p>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center" style="grid-column: 1 / -1;">Aucun vêtement disponible pour le moment.</p>
    @endforelse
</div>
@endsection