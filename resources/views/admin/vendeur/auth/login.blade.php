<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assetsLogin/styles_admin.css') }}">
    <title>CONNEXION DU PERSONNEL</title>
</head>
<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
          <!-- Tabs Titles -->
          <h2 class="active"> Se connecter </h2>
          <a href="#"><h2 class="inactive underlineHover">S'inscrire</h2></a>
      
          <!-- Icon -->
          <div class="fadeIn first">
            <img src="{{ asset('assets/images/logoOmar.png') }}" id="icon" alt="User Icon" />
          </div>
      
          <!-- Login Form -->
          <form method="POST" action="{{ route('vendor.handleLogin') }}">
            @csrf
            @method('POST')
            <input type="text" id="login" class="fadeIn second" name="email" placeholder="Email">
            @error('email')
            <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
            @enderror
            <input type="password" id="password" class="fadeIn third" name="password" placeholder="Mot de passe">
            @error('password')
            <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
            @enderror
            <input type="submit" class="fadeIn fourth" value="Se connecter">
          </form>
      
          <!-- Remind Passowrd -->
          <div id="formFooter">
            <a class="underlineHover" href="#">Mot de passe oublié ?</a>
          </div>
      
        </div>
      </div>
</body>
</html>