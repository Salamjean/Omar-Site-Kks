<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>RegistrationForm_v1 by Colorlib</title>
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
					<div class="button-container">
						<a href="{{ route('user.accueil') }}" class="home-button">Accueil</a>
					</div>
					<img src="{{ asset('assets/images/registration-form-1.jpg') }}" alt="">
				</div>
				<form method="POST" action="{{ route('user.handleRegister') }}">
                    @csrf
					<h3>S'inscrire</h3>
					<div class="form-wrapper">
						<input type="text" name="name" placeholder="Nom" value="{{ old('name') }}" class="form-control">
                        @error('name')
                        <div style="color:red">{{ $message }}</div>
                        @enderror
						<i class="zmdi zmdi-name"></i>
					</div>
                    <div class="form-wrapper">
						<input type="text" name="prenom" placeholder="Prénoms" value="{{ old('prenom') }}" class="form-control">
                        @error('prenom')
                        <div style="color:red">{{ $message }}</div>
                        @enderror
						<i class="zmdi zmdi-prenom"></i>
					</div>
					<div class="form-wrapper">
						<input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="form-control">
                        @error('email')
                        <div style="color:red">{{ $message }}</div>
                        @enderror
						<i class="zmdi zmdi-email"></i>
					</div>
					<div class="form-wrapper">
						<input type="password" name="password" placeholder="Mot de passe"  class="form-control">
						<i class="zmdi zmdi-lock"></i>
                        @error('password')
                        <div style="color:red">{{ $message }}</div>
                        @enderror
					</div>
					<div class="form-wrapper">
						<input type="password" name="password_confirm" placeholder="Confirmer mot de passe"  class="form-control">
                        @error('password_confirm')
                        <div style="color:red">{{ $message }}</div>
                        @enderror
						<i class="zmdi zmdi-lock"></i>
					</div>
                    <button type="submit" class="btn btn-primary btn-block">S'inscrire
                        <i class="zmdi zmdi-arrow-right"></i>
                    </button>
                    <div style="margin-top: 20px; text-align: center;">
						Vous avez déjà un compte? <a href="{{ route('login') }}">Se connecter</a>
                    </div>

				</form>
			</div>
		</div>
		
	</body>
</html>