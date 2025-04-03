
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Omar &mdash; Boutique en ligne</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="{{ asset('assetsUsers/fonts/icomoon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logoOmar.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assetsUsers/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsUsers/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsUsers/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsUsers/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsUsers/css/owl.theme.default.min.css') }}">


    <link rel="stylesheet" href="{{ asset('assetsUsers/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('assetsUsers/css/style.css') }}">
    
  </head>
  <body>
  
  <div class="site-wrap">
   @include('pages.layouts.navbar')

    @yield('content')

  </div>

  <script src="{{ asset('assetsUsers/js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('assetsUsers/js/jquery-ui.js') }}"></script>
  <script src="{{ asset('assetsUsers/js/popper.min.js') }}"></script>
  <script src="{{ asset('assetsUsers/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assetsUsers/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assetsUsers/js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('assetsUsers/js/aos.js') }}"></script>

  <script src="{{ asset('assetsUsers/js/main.js') }}"></script>
    
  </body>
</html>