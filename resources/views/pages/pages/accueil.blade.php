@extends('pages.layouts.templates')
@section('content')
<link rel="stylesheet" href="{{ asset('assetsUsers/accueil.css') }}">

@php
    $backgroundImage = App\Models\BackgroundImage::latest()->first();
@endphp

<!-- Section de couverture -->
<div class="site-blocks-cover" style="background-image: url({{ $backgroundImage ? asset('storage/' . $backgroundImage->image_path) : asset('assets/images/mode.avif') }});" data-aos="fade">
    <div class="container h-100">
        <div class="row align-items-center justify-content-end h-100">
            <div class="col-md-6 text-right pt-5 pt-md-0">
                <p class="mb-3" style="color: white; font-size: 80px; line-height: 1.2;">
                    NOUVEAU <br> ARRIVÉES
                </p>
                <div class="intro-text text-right">
                    <p>
                        <a href="{{ route('user.space') }}" class="btn btn-sm animated-button">Voir les nouveaux arrivées</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section hero -->
<div class="hero-section" 
     style="background-image: url('{{ $latestArticle ? asset('storage/' . $latestArticle->main_image) : asset('assets/images/mode.avif') }}');">
    <p style="font-size: 40px; margin: 0;">Vêtements</p>
    <p>
        <a href="{{ route('user.space') }}" class="btn btn-sm animated-button1">Voir plus de vêtements</a>
    </p>
</div>

<!-- Carrousel -->
<div class="carousel-container">
    <div class="nav-arrow left"><i class="fa-solid fa-chevron-left"></i></div>
    
    <div class="product-carousel">
        @if($articles->isEmpty())
            <p style="text-align: center">Aucun vêtement disponible pour le moment.</p>
        @else
            @foreach($articles as $article)
                <div class="product-item">
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $article->main_image) }}" 
                             alt="{{ $article->name }}" 
                             class="main-image">
                        <img src="{{ asset('storage/' . $article->hover_image) }}" 
                             alt="{{ $article->name }} - Back" 
                             class="hover-image">
                        <div class="quick-view bg-black"><a href="{{ route('user.space') }}" style="color: white">Commander</a></div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">{{ $article->name }}</h3>
                        <div style="display: flex; justify-content:space-around">
                            <p class="product-price">Quantité : {{ $article->nombre }}</p>
                            <p class="product-price"> Prix : {{ number_format($article->price) }} Fcfa</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="nav-arrow right"><i class="fa-solid fa-chevron-right"></i></div>
</div>

<hr>

<!-- Section texte et image -->
<h2 style="text-align: center; margin: 20px 0;">Chaussures</h2>
<div class="container1">
    <div class="text-section">
        <h1>Les chaussures <br> femmes</h1>
        <p>Découvrez notre nouvelle collection de chaussures tendance à des prix imbattables ! Que vous cherchiez des sneakers, des chaussures habillées ou des sandales, nous avons ce qu'il vous faut.</p>
        <a href="#">Voir plus de chaussures</a>
    </div>
    <div class="image-section">
        <img src="{{ $latestChaussure ? asset('storage/' . $latestChaussure->main_image) : asset('assets/images/mode.avif') }}" alt="Blazer">
    </div>
</div>
<hr>
<!-- Section Accessoires - Carrousel -->
<div class="carousel-container">
    <h2 style="text-align: center; margin: 20px 0;">Accessoires</h2>
    <div class="nav-arrow left"><i class="fa-solid fa-chevron-left"></i></div>
    
    <div class="product-carousel">
        @if($articlesAccessoire->isEmpty())
            <p style="text-align: center">Aucun accessoire disponible pour le moment.</p>
        @else
            @foreach($articlesAccessoire as $accessoire)
                <div class="product-item">
                    <div class="image-container">
                        <img src="{{ asset('storage/' . $accessoire->main_image) }}" 
                             alt="{{ $accessoire->name }}" 
                             class="main-image">
                        <img src="{{ asset('storage/' . $accessoire->hover_image) }}" 
                             alt="{{ $accessoire->name }} - Back" 
                             class="hover-image">
                        <div class="quick-view bg-black"><a href="{{ route('user.space') }}" style="color: white">Commander</a></div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-name">{{ $accessoire->name }}</h3>
                        <div style="display: flex; justify-content:space-around">
                            <p class="product-price">Quantité : {{ $accessoire->nombre }}</p>
                            <p class="product-price"> Prix : {{ number_format($accessoire->price) }} Fcfa</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="nav-arrow right"><i class="fa-solid fa-chevron-right"></i></div>
</div>

<hr>
<!-- Footer -->
<footer class="bg-black py-5" style="background-color: black">
    <div class="container">
        <div class="row">
            <!-- SUPPORT Section -->
            <div class="col-md-3">
                <h5 style="font-weight: bold; font-size:25px">Directrice générale</h5>
                <ul class="list-unstyled">
                    <li style="font-size:20px">Assita Fofana</li>
                </ul>
            </div>

            <!-- LEGAL Section -->
            <div class="col-md-3">
                <h5 style="font-weight: bold; font-size:25px">Téléphone</h5>
                <ul class="list-unstyled">
                    <li style="font-size:20px">(+225) 01 01 46 69 91</li>
                </ul>
            </div>

            <!-- CONNECT Section -->
            <div class="col-md-2">
                <h5 style="font-weight: bold; font-size:25px">Adresse</h5>
                <ul class="list-unstyled">
                    <li style="font-size:20px">Cocody, Abidjan</li>
                </ul>
            </div>

            <div class="col-md-2">
                <h5 style="font-weight: bold; font-size:25px">Site web</h5>
                <ul class="list-unstyled">
                    <li style="font-size:20px">www.omar.ci</li>
                </ul>
            </div>

            <!-- NEWSLETTER Section -->
            <div class="col-md-2">
                <h5 style="font-weight: bold; font-size:25px">Email</h5>
                <p style="font-size:20px">assita.fofana@omar.ci</p>
            </div>
        </div>
    </div>
</footer>

<!-- Script JavaScript pour le carrousel -->
<script>
    const carousel = document.querySelector('.product-carousel');
    const arrowLeft = document.querySelector('.nav-arrow.left');
    const arrowRight = document.querySelector('.nav-arrow.right');

    const getScrollAmount = () => {
        const itemWidth = document.querySelector('.product-item').offsetWidth;
        return itemWidth + 20; // 20px de gap
    };

    arrowLeft.addEventListener('click', () => {
        carousel.scrollBy({
            left: -getScrollAmount(),
            behavior: 'smooth'
        });
    });

    arrowRight.addEventListener('click', () => {
        carousel.scrollBy({
            left: getScrollAmount(),
            behavior: 'smooth'
        });
    });
</script>
@endsection