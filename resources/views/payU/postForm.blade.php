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
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7  text-center">
                    <div class="card bg-secondary border-0 mb-0">
                        <div class="card-body">
                            <div class="text-center text-muted mb-4">
                                <img src="https://i.postimg.cc/HkVLbdnb/logoc.png" width="250" alt="">

                            </div>
                            <form role="form" action="{{ env('PAYU_URL') }}" method="POST" autocomplete="off">
                                @csrf

                                @if (isset($state))
                                    @if ($state == 4)
                                        <div class="alert alert-info" role="alert">
                                            Compra Aprobada
                                        </div>
                                    @endif

                                    @if ($state == 6)
                                        <div class="alert alert-danger" role="alert">
                                            Compra Rechazada
                                        </div>
                                    @endif

                                    @if ($state == 104)
                                        <div class="alert alert-danger" role="alert">
                                            Error en la compra
                                        </div>
                                    @endif

                                    @if ($state == 7)
                                        <div class="alert alert-warning" role="alert">
                                            Transaccion pendiente
                                        </div>
                                    @endif
                                @endif
                                <div class="row">
                                    <div class="col"><label for=""><strong style="color: #15a8b2">Referencia:</strong>
                                        </label></div>
                                    <div class="col"><label for="">{{ $order->reference }}</label></div>
                                </div>

                                <div class="row">
                                    <div class="col"><label for=""><strong style="color: #15a8b2">Cliente:</strong>
                                        </label></div>
                                    <div class="col"><label
                                            for="">{{ $order->getCustomer->getUser->name . ' ' . $order->getCustomer->getUser->last_name }}</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><label for=""><strong
                                                style="color: #15a8b2">Comercio:</strong></label></div>
                                    <div class="col"><label for="">{{ $order->getCommerce->bussiness_name }}</label>
                                    </div>
                                </div>
                                <div class="container" style="height: 127px;overflow:auto; background-color: white">
                                    @php
                                    $des="";
                                    @endphp
                                    @foreach ($order->getOrderDetails as $item)
                                        @php
                                        $des.=$item->getProduct->name.' x'.$item->quantity.'|'
                                        @endphp
                                        <div class="row">
                                            <div class="col"><label for=""> <small>{{ $item->getProduct->name }}
                                                        {{ 'x' . $item->quantity }}</small></label>
                                            </div>
                                            <div class="col"><label
                                                    for="">${{ number_format($item->value * $item->quantity) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col"><label for=""> <small>Envio</small></label>
                                    </div>
                                    <div class="col"><label for="">${{ number_format($order->delivery_value) }}</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col"><label for=""><strong style="color: #15a8b2">Total</strong></label>
                                    </div>
                                    <div class="col"><label for="">${{ number_format($order->total) }}</label></div>
                                </div>
                                @if (!isset($state))
                                    <input name="merchantId" type="hidden" value="{{ env('PAYU_MERCHANTID') }}">
                                    <input name="accountId" type="hidden" value="{{ env('PAYU_ACCOUNT') }}">
                                    <input name="description" type="hidden" value="{{ $des }}">
                                    <input name="referenceCode" type="hidden" value="{{ $reference }}">
                                    <input name="amount" type="hidden" value="{{ $order->total }}">
                                    <input name="tax" type="hidden" value="0">
                                    <input name="taxReturnBase" type="hidden" value="0">
                                    <input name="currency" type="hidden" value="COP">
                                    <input name="signature" type="hidden" value="{{ $signature }}">
                                    <input type="hidden" name="extra1" value="{{ $order->id }}">
                                    <input name="buyerEmail" type="hidden"
                                        value="{{ $order->getCustomer->getUser->email }}">
                                    <input name="responseUrl" type="hidden"
                                        value="{{ env('APP_URL') . 'api/payUResponse' }}">
                                    <input name="confirmationUrl" type="hidden"
                                        value="{{ env('APP_URL') . 'api/payConfirm' }}">

                                    <div class="text-center">
                                        <button type="submit" class="btn  my-4 btn-lg btn-block"
                                            style="background-color: #2dce89; color:white;">Ir a
                                            PayU</button>
                                    </div>

                                @endif



                            </form>
                        </div>

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

</body>

</html>
