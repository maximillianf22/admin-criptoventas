<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Criptoventas | Administrador</title>
    <!-- Favicon -->
    <link href="https://i.postimg.cc/13KzKsfQ/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    @yield('css')
</head>

<body class="{{ $class ?? '' }}">

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
        <div class="container-fluid">
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Brand -->
            <a class="navbar-brand pt-0" href="/">
                <img src="https://i.postimg.cc/HkVLbdnb/logoc.png" class="navbar-brand-img" alt="...">
            </a>
            <!-- User -->
            <ul class="nav align-items-center d-md-none">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                            </span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>Mi perfil</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>Usuarios</span>
                        </a>
                        <a href="{{ route('permits.index') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>Permisos</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/" class="dropdown-item" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="ni ni-user-run"></i>
                            <span>Cerrar sesión</span>
                        </a>
                    </div>
                </li>
            </ul>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Collapse header -->
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="/">
                                <img src="https://i.postimg.cc/HkVLbdnb/logoc.png">
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                                aria-label="Toggle sidenav">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Navigation -->
                <ul class="navbar-nav">
                    @permit('dashboard')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-line text-info"></i> Dashboard
                        </a>
                    </li>
                    @endpermit

                    @permit('commercesCategory')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('category.index') }}">
                            <i class="fas fa-store text-info"></i> Categorias de comercios
                        </a>
                    </li>
                    @endpermit

                    @permit('commerces')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('commerces.index') }}">
                            <i class="fas fa-store text-info"></i> Comercios
                        </a>
                    </li>
                    @endpermit

                    @permit('categories')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="fas fa-shopping-cart text-info"></i> Categorias de productos
                        </a>
                    </li>
                    @endpermit

                    @permit('productsCommerce')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.commerce.index') }}">
                            <i class="fas fa-shopping-cart text-info"></i> Productos
                        </a>
                    </li>
                    @endpermit

                    @if (Auth::user()->rol_id != 2 || (Auth::user()->rol_id == 2 &&
                    Auth::user()->getCommerce->commerce_type_vp == 10))
                    @permit('units')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('units.index') }}">
                            <i class="fas fa-balance-scale text-info"></i> Unidades de venta
                        </a>
                    </li>
                    @endpermit
                    @endif

                    @permit('minShopping')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('minShoppingValue.index') }}">
                            <i class="fas fa-shopping-basket text-info"></i> Minimo de compra
                        </a>
                    </li>
                    @endpermit

                    @permit('tips')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tips.index') }}">
                            <i class="fas fa-hand-holding-usd text-info"></i> Propinas
                        </a>
                    </li>
                    @endpermit

                    @permit('customers')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('custommers.index') }}">
                            <i class="fas fa-users text-info"></i> Clientes
                        </a>
                    </li>
                    @endpermit

                    @permit('distributors')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('distributors.index') }}">
                            <i class="fas fa-users text-info"></i> Distribuidores
                        </a>
                    </li>
                    @endpermit

                    @permit('orders')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-file-invoice-dollar text-info"></i> Pedidos
                        </a>
                    </li>
                    @endpermit

                    @permit('shipping')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shipping.index') }}">
                            <i class="far fa-clock text-info"></i> Horas de entrega
                        </a>
                    </li>
                    @endpermit

                    {{--
                    @permit('parameters')
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-users-cog text-info"></i> Parametros
                        </a>
                    </li>
                    @endpermit

                    --}}

                    @permit('coupons')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('coupons.index') }}">
                            <i class="fas fa-ticket-alt text-info"></i> cupones
                        </a>
                    </li>
                    @endpermit
                    @permit('sliders')
                    @if(Auth::user()->rol_id == 2)
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('slider.index')}}">
                            <i class="fas fa-tags text-info"></i> Sliders
                        </a>
                    </li>
                    @endif
                    @endpermit
                    @permit('sliders')
                    @if(Auth::user()->rol_id == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('gslider.index')}}">
                            <i class="fas fa-tags text-info"></i> Sliders
                        </a>
                    </li>
                    @endif

                    @endpermit

                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="/">{{ $page ?? '' }}</a>
                <!-- User -->
                <ul class="navbar-nav align-items-center d-none d-md-flex">
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                    <img alt="Image"
                                        src="{{ !empty(Auth::user()->photo) ? asset('storage/' . Auth::user()->photo) : asset('argon') . '/img/theme/team-4-800x800.jpg' }}">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span
                                        class="mb-0 text-sm  font-weight-bold">{{ Auth::user()->rol_id != 2 ? Auth::user()->name : Auth::user()->getCommerce->bussiness_name }}</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <a href="{{ route('profile.index') }}" class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>Mi perfil</span>
                            </a>
                            @permit('users')
                            <a href="{{ route('users.index') }}" class="dropdown-item">
                                <i class="fas fa-users"></i>
                                <span>Usuarios</span>
                            </a>
                            @endpermit

                            @permit('permits')
                            <a href="{{ route('permits.index') }}" class="dropdown-item">
                                <i class="fas fa-lock"></i>
                                <span>Permisos</span>
                            </a>
                            @endpermit
                            <div class="dropdown-divider"></div>
                            <a href="/" class="dropdown-item" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="ni ni-user-run"></i>
                                <span>Cerrar sesión</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        @yield('content')
    </div>

    <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    @stack('js')


    <!-- Argon JS -->
    <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA01EIKVqGmy9BAhcDyT-nsJsLtBUbU_gA&libraries=places&callback=initMap">
    </script>
</body>

</html>