<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assetsLogin/styles_admin.css') }}">
    <title>INSCRIPTION DU PERSONNEL</title>
</head>
<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
          <!-- Tabs Titles -->
          <a href="#"><h2 class="inactive underlineHover"> Se connecter </h2></a>
          <h2 class="active" >S'inscrire </h2>
      
          <!-- Icon -->
          <div class="fadeIn first">
            <img src="{{ asset('assets/images/logoOmar.png') }}" id="icon" alt="User Icon" />
          </div>
      
          <!-- Login Form -->
          <form method="POST" action="{{ route('vendor.validate', $email) }}" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input type="email" id="name" class="fadeIn second" value="{{ $email }}" name="email" readonly>
            @error('email')
                <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
            @enderror
            <input type="text" id="login" class="fadeIn second" name="code" value="{{ old('code') }}" placeholder="Code de validation">
            @error('code')
            <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
            @enderror
            <input type="password" id="password" class="fadeIn third" name="password" placeholder="Mot de passe">
            @error('password')
            <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
             @enderror
            <input type="password" id="password" class="fadeIn third" name="password_confirm" placeholder="Confirmer mot de passe">
            @error('password_confirm')
            <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
             @enderror
             <br>
             <label for="">Photo de profil</label>
             <input type="file" id="password" class="fadeIn third" name="profile_pictures">
            @error('profile_pictures')
            <div style="color: rgb(152, 21, 21)">{{ $message }}</div>
             @enderror
            <input type="submit" class="fadeIn fourth" value="S'inscrire">
          </form>
        </div>
      </div>
</body>
</html>