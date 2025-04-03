<header class="site-navbar" role="banner">
  <div style="background-color: black; text-transform:uppercase; padding:13px;text-align:center; color:white; font-size:13px">LIVRAISON GRATUITE POUR TOUTES LES COMMANDES</div>
  <div class="site-navbar-top">
    <div class="container" style="width: 100%">
      <div class="row align-items-center">

        <!-- Barre de recherche à l'extrême gauche -->
        <div class="col-4 col-md-4 order-1 order-md-1 text-left">
          <form action="" class="site-block-top-search">
            <span class="icon icon-search2"></span>
            <input type="text" class="form-control border-0" placeholder="Search">
          </form>
          <hr>
        </div>

        <!-- Logo au centre -->
        <div class="col-4 col-md-4 order-2 order-md-2 text-center">
          <div>
            <a href="{{ route('user.space') }}" class="js-logo-clone"><img src="{{ asset('assets/images/omarnoir.png') }}" style="width: 300px; height:30px" alt=""></a>
          </div>
        </div>

        <!-- Icônes à l'extrême droite -->
        <div class="col-4 col-md-4 order-3 order-md-3 text-right">
          <div class="site-top-icons">
            <ul>
              <li><a href="#"><i class="fa-regular fa-heart"></i></a></li>
              <li>
                <a href="{{ route('commandes.index') }}" class="site-cart">
                  <i class="fa-solid fa-bag-shopping"></i>
                  <span class="count">{{ $articleCount }}</span>
                </a>
              </li> 
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa-solid fa-user"></i>
                </a>
                <ul class="dropdown-menu">
                  <li class="dropdown-header" style="text-align: center">{{ Auth::user()->name.' '.Auth::user()->prenom }}</li>
                  <li><a href="{{ route('user.logout') }}"><i class="fa-solid fa-power-off"></i> Déconnexion</a></li>
                </ul>
              </li>
              <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
            </ul>
          </div> 
        </div>

      </div>
    </div>
  </div> 
  <nav class="site-navigation text-right text-md-center" role="navigation">
    <div class="container">
      <ul class="site-menu js-clone-nav d-none d-md-block">
        <li><a href="{{ route('user.space') }}">Accueil</a></li>
        <li><a href="{{ route('user.new') }}">Nouveauté</a></li>
        <li><a href="{{ route('user.clothes') }}">Vêtements</a></li>
        <li><a href="{{ route('user.shoes') }}">Chaussures</a></li>
        <li><a href="{{ route('user.accessory') }}">Accessoires</a></li>
      </ul>
    </div>
  </nav>
</header>

<style>
  .site-menu li {
    position: relative;
    display: inline-block; 
    margin: 0 10px; 
  }

  .site-menu li a {
    text-decoration: none;
    color: inherit;
    display: block; 
    padding: 10px 0; 
    position: relative;
  }

  .site-menu li a::after {
    content: '';
    position: absolute; 
    left: 0; 
    bottom: 7px; 
    width: 0; 
    height: 2px;
    background-color: black; 
    transition: width 0.3s ease; 
  }

  .site-menu li a:hover::after {
    width: 100%;
  }

  /* Styles pour le dropdown */
  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-toggle {
    cursor: pointer;
    padding: 5px 10px;
  }

  .dropdown-menu {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 200px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 4px;
    border: 1px solid #eee;
  }

  .dropdown-menu li {
    padding: 0;
    margin: 0;
    list-style: none;
  }

  .dropdown-menu a {
    color: #333;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    text-align: left;
    transition: background-color 0.3s;
  }

  .dropdown-menu a:hover {
    background-color: #f8f9fa;
  }

  .dropdown:hover .dropdown-menu {
    display: block;
  }

  .dropdown-header {
    padding: 10px 15px;
    font-weight: bold;
    color: #333;
    border-bottom: 1px solid #eee;
    display: block;
  }

  .dropdown-menu i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
  }

  /* Ajustements pour les petits écrans */
  @media (max-width: 767.98px) {
    .site-navbar-top .row {
      flex-wrap: nowrap;
    }
    .site-navbar-top .col-4 {
      flex: 0 0 33.333333%;
      max-width: 33.333333%;
    }
    .site-navbar-top .site-block-top-search {
      width: 100%;
    }
    .site-navbar-top .js-logo-clone img {
      width: 10%;
      height: 10px;
    }
    .dropdown-menu {
      right: auto;
      left: 0;
    }
  }
</style>

<script>
  // Script pour gérer le dropdown
  document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
      dropdown.addEventListener('click', function(e) {
        const menu = this.querySelector('.dropdown-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        e.stopPropagation();
      });
    });
    
    // Fermer le dropdown quand on clique ailleurs
    window.addEventListener('click', function() {
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.style.display = 'none';
      });
    });
  });
</script>