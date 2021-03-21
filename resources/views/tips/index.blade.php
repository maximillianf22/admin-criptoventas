@extends('layouts.app', ['page' => 'Propinas'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5 ">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i
                                        class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('tips.index')}}">Pedidos</a></li>
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
        <div class="col-4">
            <div class="card shadow bg-secondary">
                <div class="card-header">
                    <h3 class="mb-0">Crear valores para propinas</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('tips.store')}}" autocomplete="off">
                        @csrf
                        @if(Auth::user()->rol_id != 2)
                        <div class="form-group">
                            <label>Comercio</label>
                            <select name="commerce_id" class="form-control" required>
                                <option value="" selected disabled>Seleccione un comercio</option>
                                @foreach($commerces as $commerce)
                                <option value="{{$commerce->id}}">{{$commerce->bussiness_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="commerce_id" value="{{Auth::user()->getCommerce->id}}">
                        <input type="text" disabled value="{{Auth()::user()->getCommerce->bussiness_name}}">
                        @endif
                        <div class="form-group">
                            <label>Valor </label>
                            <input type="text" name="value" id="input-name"
                                class="form-control form-control-alternative" placeholder="Valor..." required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                - {{$error}} <br>
                @endforeach
            </div>
            @endif
            @if (Session::has('success'))
            <div class="alert alert-info">
                {{ session('success') }}
            </div>
            @endif
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Propinas</h3>
                        </div>
                        <div class="col text-right">
                            <button class="btn btn-info " id="filterbtn">Filtros <i class="fas fa-filter"></i></button>
                        </div>
                    </div>
                    <form action="" style="display: none" id="toggleform" method="get">
                        <div class="row align-items-center">
                            <div class="col ">
                                <div class="form-group mb-1">
                                    <label>Comercio </label>
                                    <input type="text" name="bussiness_name" class="form-control"
                                        placeholder="Comercio " value="{{request()->bussiness_name}}">
                                </div>
                                <div class="form-group mb-1">
                                    <label>Valor</label>
                                    <input type="text" name="value" class="form-control" placeholder="Valor"
                                        value="{{request()->value}}">
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
                        </div>
                        <div class="col">
                            <button class="btn btn-info btn-block ">Filtrar</button>
                            <a href="/administrator/tips" class="btn btn-info btn-block btnFilterEraseTips">Borrar</a>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Comercio</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="store-b-manager">
                            @if (count($tips)>0)
                            @foreach ($tips as $tip)
                            <tr id="{{$tip->id}}">
                                <td>{{$tip->getCommerce->bussiness_name}}</td>
                                <td>{{$tip->value}}%</td>
                                <td>{{Config::get('const.states')[$tip->state]}}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning btnEditTips"><i
                                            class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger btnDeleteTips"><i
                                            class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="pad-all text-center">No se encontraron registros
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
<!-- Editar -->
<div class="modal fade" id="modalEdittips" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm " role="document">
        <form id="formUpdateTips" method="POST">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar propina</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Comercio</label>
                        <input type="hidden" class="commerce_id" name="commerce_id">
                        <select id="commerce_id" class="form-control" disabled>
                            <option value="" selected disabled>Seleccione un comercio</option>
                            @foreach($commerces as $commerce)
                            <option value="{{$commerce->id}}">{{$commerce->bussiness_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Valor </label>
                        <input type="text" name="value" id="tips" class="form-control form-control-alternative"
                            placeholder="Valor..." required>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="state" id="state" class="form-control">
                            @foreach(Config::get('const.states') as $id => $value)
                            <option value="{{$id}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
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