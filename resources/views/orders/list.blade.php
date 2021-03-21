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
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
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
            <div class="card shadow ">
                <div class="card-header border-0">
                    <div class="container">

                        <div class="row">
                            <div class="col offset-9">
                                <button class="btn btn-info " id="filterbtn">Filtros <i
                                        class="fas fa-filter"></i></button>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('orders.index') }}" style="display: none" id="toggleform" method="get">

                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-group">
                                    <label>Referencia</label>
                                    <input type="text" name="reference" class="form-control"
                                        value="{{request()->reference}}" placeholder="Referencia del pedido">
                                </div>
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" name="customer" value="{{request()->customer}}"
                                        class="form-control" placeholder="Cliente">
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="form-group col">
                                        <label>Fecha inicio</label>
                                        <input type="date" name="init_date" class="form-control"
                                            value="{{request()->init_date}}" style="font-family: monospace !important;">
                                    </div>
                                    <div class="form-group col">
                                        <label>Fecha final</label>
                                        <input type="date" name="fin_date" class="form-control"
                                            value="{{request()->fin_date}}" style="font-family: monospace !important;">
                                    </div>
                                </div>

                            </div>
                            <div class="col">

                                <div class="form-group">
                                    <label>Pedidos</label>

                                    <select class="form-control" name="pedidos" id="">
                                        <option value="{{null}}" {{empty(request()->pedidos)?'selected ':''}}>seleccione
                                            estado de pedido</option>
                                        @foreach ($state as $item)
                                        <option {{$item->id==request()->pedidos?'selected ':''}} value="{{ $item->id}}">
                                            {{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Pagos</label>

                                    <select class="form-control" name="pagos" id="">
                                        <option value="{{null}}" {{empty(request()->pagos)?'selected ':''}}>seleccione
                                            estado de pago</option>
                                        @foreach ($state2 as $item)
                                        <option {{$item->id==request()->pagos?'selected ':''}} value="{{ $item->id}}">
                                            {{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Comercio</label>
                                    <input type="text" name="commerce" class="form-control"
                                        value="{{request()->commerce}}" placeholder="Nombre del comercio">
                                </div>
                            </div>
                            <div class="col">
                                <button class="btn btn-info btn-block ">Filtrar</button>
                                <a href="{{ route('orders.index') }}" class="btn btn-info btn-block  ">Borrar</a>
                            </div>
                        </div>

                    </form>
                </div>
                @if ($orders->count()>0)
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush text-center">
                        <thead class="thead-light text-center">
                            <tr>
                                <th></th>
                                <th scope="col">#</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Comercio</th>
                                <th scope="col">Fecha <br> del pedido</th>
                                <th scope="col">Fecha <br> de entrega </th>
                                <th scope="col">Estado</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">

                            @foreach ($orders->reverse() as $item)
                            <tr id="{{ $item->id }}">
                                <td class="pl-1 pr-1">
                                    <a class="btn btn-sm btn-warning" href="{{ route('orders.show', [$item->id]) }}"><i
                                            class="fas fa-edit"></i></a>
                                </td>
                                <td class="pl-1 pr-1">{{ $item->reference }} </td>
                                <td class="pl-1 pr-1">{{ $item->getCustomer->getUser->name }}</td>
                                <td class="pl-1 pr-1">{{ $item->getCommerce->bussiness_name }}</td>
                                <td class="pl-1 pr-1">{{ $item->date }}</td>
                                <td class="pl-1 pr-1">{{ $item->time }}</td>
                                <td class="pl-1 pr-1">
                                    <div class="badge badge-{{$item->getOrderState->id!=23?'success':'danger'}}">
                                        {{ $item->getOrderState->name }} <br>
                                    </div> <br>
                                    <div class="badge badge-default">
                                        {{ $item->getOrderPaymentState->name }}
                                    </div>
                                </td>
                                <td class="pl-1 pr-1">${{ number_format($item->total) }}</td>

                            </tr>
                            @endforeach



                        </tbody>
                    </table>
                </div>
                @else
                <div class="row">
                    <div class="col offset-5">
                        <h5>Sin resultados &#128549;</h5>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$("#filterbtn").click(function() {
    $("#toggleform").toggle("slow");
});
</script>

@endpush
