@extends('layouts.app', ['page' => 'Productos'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('products.commerce.index')}}">Elegir un comercio</a></li>
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
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                - {{$error}} <br>
                @endforeach
            </div>
            @endif
            <div class="card shadow">
                <div class="card-header border-0">
                    <form action="{{route('products.commerce.index')}}" method="get">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Comercio</label>
                                    <input type="text" name="bussiness_name" class="form-control" placeholder="Comercio" value="{{request()->bussiness_name}}">
                                </div>
                                <div class="form-group mb-1">
                                    <label>NIT</label>
                                    <input type="text" name="nit" class="form-control" placeholder="NIT" value="{{request()->nit}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Tipo de comercio</label>
                                    <select name="commerce_type" class="form-control">
                                        <option value="-1" {{ request()->commerce_type == -1 || is_null(request()->commerce_type) ? 'selected' : ''}}>Todos</option>
                                        @foreach($commerceTypes as $type)
                                        <option value="{{$type->id}}" {{request()->commerce_type == $type->id ? 'selected': ''}}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label>Estado</label>
                                    <select name="state" class="form-control">
                                        <option value="-1" {{ request()->state == -1 || is_null(request()->state) ? 'selected' : ''}}>Todos</option>
                                        @foreach(Config::get('const.states') as $state => $value)
                                        <option value="{{$state}}" {{request()->state === "".$state ? 'selected' : ''}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <button class="btn btn-info btn-block ">Filtrar</button>
                                <button class="btn btn-info btn-block btnFilterEraseListCommerces">Borrar</button>
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
                                <th scope="col">Nit</th>
                                <th scope="col">Comercio</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Celular</th>
                                <th scope="col">Tipo de comercio</th>
                                <th scope="col">Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($commerces->count())
                            @foreach($commerces as $commerce)
                            <tr id="{{$commerce->id}}">
                                <td></td>
                                <td>{{$commerce->nit}}</td>
                                <td>{{$commerce->bussiness_name}}</td>
                                <td>{{$commerce->getUser->name}} {{$commerce->getUser->last_name}}</td>
                                <td>{{$commerce->getUser->cellphone}}</td>
                                <td>{{$commerce->getCommerceType->name}}</td>
                                <td>{{Config::get('const.user_states')[$commerce->state]}}</td>
                                <td>
                                    <a class="btn btn-sm btn-info" href="{{route('products.commerce.show', [$commerce->id])}}">Ver productos</a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">
                                    No hay comercios registrados
                                </td>
                            </tr>
                            @endif
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
