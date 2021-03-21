@extends('layouts.app', ['page' => 'Comercios'])

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
                            <li class="breadcrumb-item"><a href="{{route('commerces.index')}}">Comercios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{route('commerces.create')}}" class="btn btn-neutral">Crear un comercio</a>
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
                {{session('success')}}
            </div>
            @endif
            <div class="card shadow">
                <div class="card-header border-0">
                    <form action="{{route('commerces.index')}}" method="get">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Comercio</label>
                                    <input type="text" name="bussiness_name" class="form-control" placeholder="Comercio"
                                        value="{{request()->bussiness_name}}">
                                </div>
                                <div class="form-group mb-1">
                                    <label>NIT</label>
                                    <input type="text" name="nit" class="form-control" placeholder="NIT"
                                        value="{{request()->nit}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Tipo de comercio</label>
                                    <select name="commerce_type" class="form-control">
                                        <option value="-1"
                                            {{ request()->commerce_type == -1 || is_null(request()->commerce_type) ? 'selected' : ''}}>
                                            Todos</option>
                                        @foreach($commerceTypes as $type)
                                        <option value="{{$type->id}}"
                                            {{request()->commerce_type == $type->id ? 'selected': ''}}>{{$type->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
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
                                <button class="btn btn-info btn-block btnFilterEraseCommerce">Borrar</button>
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
                                <th scope="col">Comercio</th>
                                <th scope="col">NIT</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                                <th scope="col">Sliders</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($commerces->count())
                            @foreach($commerces as $comercio)
                            <tr id="{{$comercio->id}}">
                                <td></td>
                                <td>{{$comercio->bussiness_name}} </td>
                                <td>{{$comercio->nit }}</td>
                                <td>{{$comercio->getCommerceType->name}}</td>
                                <td>
                                    @if($comercio->state == 3)
                                    Pendiente por aprobación
                                    @else
                                    {{$comercio->state==1?'Activado':'Desactivado' }}</td>
                                @endif
                                <td>
                                    <a class="btn btn-sm btn-warning"
                                        href="{{route('commerces.edit', [$comercio->id])}}"><i
                                            class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger btnDeleteCommerces"><i
                                            class="fas fa-trash-alt"></i></button>
                                </td>
                                <td>
                                    @if($comercio->state != 3)
                                    <a class="btn btn-block btn-info"
                                        href="{{route('sliders.view', ['id'=>$comercio->id])}}"><i
                                            class="fas fa-images"></i></a>

                                    @endif
                                </td>
                            </tr>
                            @endforeach
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
