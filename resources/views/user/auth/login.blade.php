@extends('pages.layouts.templates')
@section('content')

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Se connecter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- MATERIAL DESIGN ICONIC FONT -->
    <link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="{{ asset('assetsLogin/styles_user.css') }}">
</head>

<body>
    <div class="wrapper" style="background-image: url('{{ asset('assets/images/mode.avif') }}');">
        <div class="inner">
            <div class="image-holder">
                <img src="{{ asset('assets/images/registration-form-1.jpg') }}" alt="">
            </div>
            <form method="POST" action="{{ route('user.handleLogin') }}">
                @csrf
                <h3>Se connecter</h3>
                <div class="form-wrapper">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="form-control">
                    @error('email')
                    <div style="color:red">{{ $message }}</div>
                    @enderror
                    <i class="zmdi zmdi-email"></i>
                </div>
                <div class="form-wrapper">
                    <input type="password" name="password" placeholder="Mot de passe" class="form-control">
                    @error('password')
                    <div style="color:red">{{ $message }}</div>
                    @enderror
                    <i class="zmdi zmdi-lock"></i>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter
                    <i class="zmdi zmdi-arrow-right"></i>
                </button>
                <div style="margin-top: 20px; text-align: center;">
                    Pas encore de compte? <a href="{{ route('user.register') }}">S'inscrire</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
@endsection