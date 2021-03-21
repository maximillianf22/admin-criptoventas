<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Argon Dashboard') }}</title>
    <!-- Favicon -->
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('argon') }}/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">

</head>

<body class="bg-default g-sidenav-show g-sidenav-pinned">
    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-info py-7">
            <div class="container">
                <div class="header-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        </div>
                    </div>
                </div>
            </div>
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 text-center">
                    <div class="card bg-secondary border-0 mb-0" style="width: 30vw;">
                        <div class="card-body">
                            <div class="text-center text-muted mb-4">
                                <img src="https://i.postimg.cc/HkVLbdnb/logoc.png" width="250" alt="">
                                <h2>Administrador</h2>
                            </div>
                            <form role="form" action="{{route('login')}}" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" name="cellphone" placeholder="Celular" type="number">
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" id="passwordInput" name="password" placeholder="Contraseña" type="password">
                                    </div>
                                </div>
                                <!-- An element to toggle between password visibility -->
                                <div class="form-group text-right mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input showPassCheck" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Mostrar contraseña</label>
                                    </div>
                                </div>
                                @if($errors->any())
                                <p class="m-0 text-danger">Usuario o contraseña incorrectos. Si el problema persiste, por favor contacte al adminstrador.</p>
                                @endif
                                @if(session('failed'))
                                <p class="m-0 text-danger">{{ session('failed') }}</p>
                                @enderror
                                <div class="text-center">
                                    <button type="submit" class="btn btn-info my-4">Ingresar</button>
                                </div>
                                <a class="text-left" href="{{route('auth.confirmCellphone')}}"><small>Olvidé mi contraseña</small></a>
                            </form>
                        </div>
                        @if(session('success'))
                        <div class="alert alert-info">
                            {{session('success')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="{{ asset('argon') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Argon JS -->
    <script src="{{ asset('argon') }}/js/argon.js?v=1.0.0"></script>
    <script>
        $('.showPassCheck').on('click', function() {
            let input = document.getElementById("passwordInput");
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        })
    </script>
</body>

</html>