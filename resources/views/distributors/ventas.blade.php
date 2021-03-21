@extends('layouts.app', ['page' => 'Distribuidores ventas'])

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
                                <li class="breadcrumb-item"><a href="{{ route('distributors.index') }}">Distribuidores</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Listado</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="{{ route('distributors.create') }}" class="btn btn-neutral">Crear un distribuidor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-3">
        <div class="row mt-5">
            <div class="col">
                @if (Session::has('success'))
                    <div class="alert alert-info">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card shadow">
                    <div class="card-header border-0">

                        {{-- <form action="{{ route('distributors.index') }}" method="get">
                            --}}
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="form-group mb-1">
                                        <label>Distribuidor:</label>
                                        <input type="text" disabled class="form-control" placeholder="Documento"
                                            value="{{ $distributor->getUser->name . '' . $distributor->getUser->last_name }}">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label>total ventas</label>
                                        <input type="text" disabled class="form-control" placeholder="Nombre completo"
                                            value="${{ number_format($total) }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-1">
                                        <label>Codigo Distribuidor:</label>
                                        <input type="text" disabled class="form-control" placeholder="Celular"
                                            value="{{ $distributor->distributor_code }}">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label>comision %:</label>
                                        <input type="text" disabled class="form-control" placeholder="Celular"
                                            value=" {{ $distributor->distributor_percent }}">
                                    </div>
                                </div>

                            </div>
                            {{-- </form> --}}
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th scope="col">Referencia</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col"> Comision</th>
                                    <th scope="col">valor comision</th>
                                    <th scope="col">subtotal</th>
                                    <th scope="col">Total venta</th>
                                    <th scope="col">fecha</th>
                                    <!-- <th scope="col">Perfil</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comissions as $comission)
                                @if($comission->getOrder)
                                    <tr id="{{ $distributor->id }}">
                                        <td></td>
                                        <td><a href="{{ route('orders.show', ['order' => $comission->getOrder->id]) }}">
                                                {{ $comission->getOrder->reference }}</a></td>
                                        <td>{{ $comission->getOrder->getCustomer->getUser->name }}
                                            {{ $comission->getOrder->getCustomer->getUser->last_name }}</td>
                                        <td>{{ $comission->distributor_percent }}% </td>
                                        <td>${{ number_format(($comission->distributor_percent / 100) * $comission->getOrder->sub_total) }}
                                        </td>
                                        <td>${{ number_format($comission->getOrder->sub_total) }}</td>
                                        <td>${{ number_format($comission->getOrder->total) }}</td>
                                        <td>{{ $comission->created_at }}</td>
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush
