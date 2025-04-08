@extends('pages.layouts.templates')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsUsers/pages.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assetsUsers/pages/new.js') }}"></script>

<h1 class="text-center mt-4" style="background-color: black; color:white">Les nouveautés (publiés dans les 2 derniers jours)</h1>

<!-- Formulaire de filtrage -->
<form method="GET" action="{{ url()->current() }}" id="filter-form">
    <div class="filter-container" style="display: flex; justify-content: center; gap: 20px; margin: 20px 0;">
        @foreach($categories as $category)
            <div class="form-check" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="categories[]" 
                       id="category-{{ $loop->index }}" 
                       value="{{ $category }}"
                       @if(in_array($category, request()->input('categories', []))) checked @endif
                       style="width: 20px; height: 20px; cursor: pointer; margin-bottom:8px">
                <label for="category-{{ $loop->index }}" style="cursor: pointer; font-size:20px; font-weight:bold">
                    {{ $category }}
                </label>
            </div>
        @endforeach
    </div>
</form>

<div class="products-grid-container">
    @forelse($newItems as $newItem)
        <div class="product-item">
            <h4 class="text-center text-black">{{ $newItem->categorie }}</h4>
            <div class="image-container">
                <img src="{{ asset('storage/' . $newItem->main_image) }}"
                     alt="{{ $newItem->name }}"
                     class="main-image">
                <img src="{{ asset('storage/' . $newItem->hover_image) }}"
                     alt="{{ $newItem->name }} - Back"
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
                <h3 class="product-name">{{ $newItem->name }}</h3>
                <div style="display: flex; justify-content:space-around">
                    <p class="product-price">Stock : {{ $newItem->nombre }}</p>
                    <p class="product-price">Prix : {{ number_format($newItem->price) }} Fcfa</p>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center" style="grid-column: 1 / -1;">Aucun article publié récemment.</p>
    @endforelse
</div>


@endsection