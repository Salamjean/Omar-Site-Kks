@extends('admin.vendeur.layouts.template')

@section('content')
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Carte Chaussure -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center justify-content-between">
                            <div class="avatar flex-shrink-0 ">
                                <img src="{{ asset('assets/images/habit.jpg') }}" alt="Chaussure" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                    <a class="dropdown-item" href="{{ route('article.vetements') }}">Voir plus</a>
                                </div>
                            </div>
                        </div>
                       <div class="d-flex justify-content-around">
                        <h3 class="card-title text-nowrap mb-1">Vêtements</h3>  
                        <h3 class="card-title text-danger mb-1">{{ $vetementsCount }}</h3> 
                       </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/images/chaussure.jpg') }}" alt="Chaussure" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                    <a class="dropdown-item" href="{{ route('article.chaussures') }}">Voir plus</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around">
                          <h3 class="card-title text-nowrap mb-1">Chaussures</h3>  
                          <h3 class="card-title text-danger mb-1">{{ $chaussureCount }}</h3> 
                         </div>
                    </div>
                </div>
            </div>

            <!-- Carte Accessoire -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/images/accessoire.jpg') }}" alt="Accessoire" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item" href="{{ route('article.accessoires') }}">Voir plus</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around">
                          <h3 class="card-title text-nowrap mb-1">Accessoires</h3>  
                          <h3 class="card-title text-danger mb-1">{{ $accessoireCount }}</h3> 
                         </div>
                    </div>
                </div>
            </div>

            <!-- Carte Total article -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{ asset('assets/images/logoOmar.png') }}" alt="Total Article" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                    <a class="dropdown-item" href="{{ route('article.index') }}">Voir plus</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around">
                          <h3 class="card-title text-nowrap mb-1">Total</h3>  
                          <h3 class="card-title text-danger mb-1">{{ $total }}</h3> 
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection