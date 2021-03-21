@extends('layouts.app', ['page' => 'Pedidos'])

@section('content')
    <div class="header bg-gradient-info pb-5 pt-5">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('administrator.home') }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pedidos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Detalle</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-3">
        <div class="row mt-5">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0 pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0 text-uppercase text-Default"> ORDEN#{{ $data->reference }}</h3>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-shopping-cart"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Nombre cliente</b><br>
                                <span
                                    style="font-size:14px">{{ $data->getCustomer->getUser->name . ' ' . $data->getCustomer->getUser->last_name }}</span>
                            </div>
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Celular</b><br>
                                <span style="font-size:14px">{{ $data->getCustomer->getUser->cellphone }}</span>
                            </div>
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Direcion</b><br>
                                <span style="font-size:14px">{{ $data->getAddress->name }} |
                                    {{ $data->getAddress->observation }}</span>
                                <span style="font-size:14px">{{ $data->getAddress->address }}</span>
                            </div>
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Comercio:</b><br>
                                <span style="font-size:14px">{{ $data->getCommerce->bussiness_name }}</span>
                            </div>
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Fecha de entrega</b><br>
                                <span style="font-size:14px">{{ $data->time }}</span>
                            </div>
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Estado del pago</b><br>
                                <span style="font-size:14px">{{ $data->getOrderPaymentState->name }}</span>
                            </div>
                            @isset($data->observation)
                            <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Observación</b><br>
                                <textarea name="" value="{{ $data->observation}}" disabled="disabled" cols="30" rows="5" style="font-size: .9rem;">{{ $data->observation}}</textarea>                           
                            </div>
                            @else
                                <div class="col-12">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Observación</b><br>
                                <textarea name="" value="" disabled="disabled" cols="30" rows="5" style="font-size: .9rem;">No hay observaciones para esta orden.</textarea>
                            </div>
                            @endisset

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="card shadow" style="max-height: 75vh; overflow: auto;">
                    <div class="card-header p-0">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->getOrderDetails as $item)
                                    <tr>
                                        <td class="text-center">
                                            @if (Storage::disk('public')->exists($item->getProduct->img_product))
                                                <img class="profile-user-img rounded-circle d-block " width="70"
                                                    src="{{ asset('storage/' . $item->getProduct->img_product) }}"
                                                    alt="User profile picture">
                                            @else
                                                <img class="profile-user-img rounded-circle d-block " width="70"
                                                    src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg"
                                                    alt="User profile picture">
                                            @endif
                                            <p class="mt-2 text-green">${{ number_format($item->total_value) }}</p>
                                        </td>
                                        <td>
                                            <h4>{{ $item->name }}
                                                <small>({{ !is_null($item->unit) ? $item->unit : '' }}x{{ $item->quantity }})</small>
                                                @isset($item->observation)
                                                <br>
                                                <small>Observación : {{$item->observation}}</small>
                                                @endisset
                                            </h4>
                                            <div style="overflow: auto; max-height: 35vh;">
                                                @if (!is_null($item->product_config) && !is_null(json_decode($item->product_config)) && is_array(json_decode($item->product_config)) > 0)
                                                    @foreach (json_decode($item->product_config) as $ingredientCat)
                                                        <h5>{{ $ingredientCat->category_name }}</h5>
                                                        @foreach ($ingredientCat->get_ingredients as $ingredient)
                                                            <h6>{{ $ingredient->ingredient_name }}
                                                                (x{{ $ingredient->ingredient_quantity }})</h6>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow mb-2">
                    <div class="card-header border-0 pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Detalle factura</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Tipo de pago</b><br>
                                <span style="font-size:14px">{{ $vp->name }}</span>
                            </div>
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Domcilio</b><br>
                                <span style="font-size:14px">${{ number_format($data->delivery_value) }}</span>
                            </div>
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Cupon</b><br>
                                <span class="text-green" style="font-size:14px">-
                                    ${{ number_format($data->coupon_value) }}</span>
                            </div>
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Propina</b><br>
                                <span style="font-size:14px"> ${{ number_format($data->tip_value) }}</span>
                            </div>
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">SubTotal</b><br>
                                <span style="font-size:14px">$ {{ number_format($data->sub_total) }}</span>

                            </div>
                            <div class="col-6">
                                @if (!empty($data->getComission))
                                    <b style="font-size:10px; color:#999"
                                        class="text-primary  font-weight-bold text-uppercase">COD Distribidor</b><br>
                                    <span style="font-size:14px">{{ $data->getComission->distributor_code }}</span>
                                @endif
                            </div>
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Total</b><br>
                                <span style="font-size:14px">${{ number_format($data->total) }}</span>
                            </div>
                            <div class="col-6">
                                @if (!empty($data->getComission))
                                    <b style="font-size:10px; color:#999"
                                        class="text-primary  font-weight-bold text-uppercase">valor comision</b><br>
                                    <span
                                        style="font-size:14px">{{ number_format(($data->getComission->distributor_percent / 100) * $data->sub_total) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-header border-0 pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Estado del pedido</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <b style="font-size:10px; color:#999"
                                    class="text-primary  font-weight-bold text-uppercase">Estado</b><br>
                                <span style="font-size:14px">{{ $data->getOrderState->name }}</span>
                            </div>
                            @if ($data->order_state != 23)
                                @if (!is_null($nextState) && $data->order_state == 14)
                                    <div class="col-6 text-right">
                                        <button class="btn btn-info btnChangeState" id="{{ $data->id }}"
                                            data-state="{{ $nextState->id }}">Aceptar</button>
                                    </div>
                                @endif
                                @if (!is_null($nextState) && $data->order_state != 14)
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-info btn-block btnChangeState" id="{{ $data->id }}"
                                            data-state="{{ $nextState->id }}">Cambiar a {{ $nextState->name }}</button>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <a class="btn btn-warning btn-block"
                                            href="{{ config('app.domiciliosApp') . 'tracking/' . $data->reference }}"
                                            target="_blank">Tracking de domicilio</a>
                                    </div>
                                @endif
                                @if ($data->payment_state!=20  && $data->order_state!=18)
                                <div class="col-12 mt-3">
                                    <form action="{{ route('cancelOrder')}}" method="post" id="canceleForm">
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        <button type="button"href= '#' class="btn btn-danger btn-block canceleOrder"
                                            >Cancelar Order</button>
                                    </form>
                                </div>
                                @endif
                                <div class="col-12 mt-3">
                                    <a href="{{ route('factura', ['id' => $data->id]) }}" class="btn btn-info btn-block"
                                        target="_blank">Imprimir facturas</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush
