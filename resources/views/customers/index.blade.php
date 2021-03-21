@extends('layouts.app', ['page' => 'Clientes'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i
                                        class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('custommers.index')}}">Clientes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
                @if(Auth::user()->rol_id != 2)
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{route('custommers.create')}}" class="btn btn-neutral">Crear cliente</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">

    <div class="row mt-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <form action="{{route('custommers.index')}}" method="get">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Celular</label>
                                    <input type="text" name="cellphone" class="form-control" placeholder="Celular"
                                        value="">
                                </div>
                                <div class="form-group mb-1">
                                    <label>Nombre completo</label>
                                    <input type="text" name="fullname" class="form-control"
                                        placeholder="Nombre completo" value="">
                                </div>
                            </div>
                            <div class="col">

                                <div class="form-group mb-1">
                                    <label>Estado</label>
                                    <select name="state" class="form-control">
                                        <option value="-1"
                                            {{ request()->state == -1 || is_null(request()->state) ? 'selected' : ''}}>
                                            Todos</option>
                                        @foreach(Config::get('const.states') as $state => $value)
                                        <option value="{{$state}}" {{request()->state === "".$state ? 'selected' : ''}}>
                                            {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <button class="btn btn-info btn-block ">Filtrar</button>
                                <button class="btn btn-info btn-block ">Borrar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th scope="col">Nombre completo</th>
                                <th scope="col">Correo electrónico</th>
                                <th scope="col">Celular</th>
                                <th scope="col">Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr id="{{$customer->id}}">
                                <td></td>
                                <td>{{$customer->getUser->name}} {{$customer->getUser->last_name}}</td>
                                <td>{{$customer->getUser->email ? $customer->getUser->email : 'No tiene'}}</td>
                                <td>{{$customer->getUser->cellphone}}</td>
                                <td>{{Config::get('const.user_states')[$customer->getUser->user_state]}}</td>
                                <td>
                                    <a class="btn btn-warning btn-sm"
                                        href="{{route('custommers.edit', [$customer->id])}}"><i
                                            class="fas fa-edit"></i></a>
                                    <button class="btn btn-danger btn-sm btnDeleteCustomer"><i
                                            class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
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
