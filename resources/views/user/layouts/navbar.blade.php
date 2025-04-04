<header class="site-navbar" role="banner">
  <link rel="stylesheet" href="{{ asset('assetsUsers/nav.css') }}">
  <div style="background-color: black; text-transform:uppercase; padding:13px;text-align:center; color:white; font-size:13px">LIVRAISON GRATUITE POUR TOUTES LES COMMANDES</div>
  <div class="site-navbar-top">
    <div class="container" style="width: 100%">
      <div class="row align-items-center">


        <!-- Barre de recherche - cachée sur mobile et tablette -->
        <div class="col-4 col-lg-4 order-1 order-lg-1 text-left d-none d-lg-block">
          <form action="" class="site-block-top-search">
            <span class="icon icon-search2"></span>
            <input type="text" class="form-control border-0" placeholder="Search">
          </form>
          <hr>
        </div>

        <!-- Logo au centre -->
        <div class="col-8 col-lg-4 order-2 order-lg-2 text-center">
          <div>
            <a href="{{ route('user.space') }}" class="js-logo-clone logo">
              <img src="{{ asset('assets/images/omarnoir.png') }}" class="img-fluid" style="max-width: 300px; height: auto;" alt="">
            </a>
          </div>
        </div>

        <!-- Icônes à l'extrême droite -->
        <div class="col-4 col-lg-4 order-3 order-lg-3 text-right">
          <div class="site-top-icons">
            <ul>
              <!-- Icônes cachées sur mobile et tablette -->
              <li class="d-none d-lg-inline-block"><a href="#" class="search-toggle"><i class="icon-search2"></i></a></li>
              <li class="d-none d-lg-inline-block"><a href="#"><i class="fa-regular fa-heart"></i></a></li>
              
              <!-- Icônes toujours visibles -->
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
              <li class="d-inline-block d-lg-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
            </ul>
          </div> 
        </div>

      </div>
    </div>
  </div> 
  
  <!-- Barre de recherche mobile (cachée par défaut) -->
  <div class="d-lg-none mobile-search" style="display: none; padding: 10px; background: #f8f9fa;">
    <form action="" class="w-100">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Rechercher...">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button"><i class="icon-search2"></i></button>
        </div>
      </div>
    </form>
  </div>
  
  <nav class="site-navigation text-right text-lg-center" role="navigation">
    <div class="container">
      <ul class="site-menu js-clone-nav d-none d-lg-block">
        <li><a href="{{ route('user.space') }}">Accueil</a></li>
        <li><a href="{{ route('user.new') }}">Nouveauté</a></li>
        <li><a href="{{ route('user.clothes') }}">Vêtements</a></li>
        <li><a href="{{ route('user.shoes') }}">Chaussures</a></li>
        <li><a href="{{ route('user.accessory') }}">Accessoires</a></li>
      </ul>
    </div>
  </nav>
</header>



<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Gestion du dropdown
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
    
    // Menu mobile
    const menuToggle = document.querySelector('.js-menu-toggle');
    const siteMenu = document.querySelector('.site-menu');
    
    if (menuToggle && siteMenu) {
      menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        siteMenu.classList.toggle('active');
      });
    }
  });
</script>