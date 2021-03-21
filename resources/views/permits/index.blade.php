@extends('layouts.app', ['page' => 'Permisos'])

@section('content')
<div class="header bg-gradient-info pb-7 pt-5 pt-md-7">
    <div class="container-fluid">
    </div>
</div>
<div class="container-fluid mt--7">
    <div class="row mt-5">
        <div class="col-5">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Roles</h3>
                        </div>
                        <div class="col text-right">
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalCreateRol">Crear rol</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Rol</th>
                                <th scope="col">Estado</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($roles->count())
                            @foreach($roles as $rol)
                            <tr id="{{$rol->id}}">
                                <td>{{$rol->name}}</td>
                                <td>{{Config::get('const.states')[$rol->state]}}</td>
                                <td>
                                    @if($rol->id != 1 && $rol->id !=2)
                                    <button class="btn btn-warning btn-sm btnEditRol"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn btn-danger btn-sm btnEraseRol"><i class="fas fa-trash-alt"></i></button>
                                    @endif
                                    @if($rol->id != 1)
                                    <button class="btn btn-info btn-sm btnPermits"><i class="fas fa-lock"></i></button>
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
        <div class="col-7">
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                - {{$error}} <br>
                @endforeach
            </div>
            @endif
            @isset($success)
            <div class="alert alert-succes">
                {{ $success}}
            </div>
            @endisset
            @if (Session::has('success'))
            <div class="alert alert-info">
                {{ session('success') }}
            </div>
            @endif
            <form action="{{route('permits.store')}}" method="POST">
                @csrf
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Modulos</h3>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-primary">Guardar permisos</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="rol_id" id="idRol">
                        <div class="row">
                            @foreach($modules as $module)
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="permits[{{$module->id}}]" value="0">
                                    <input type="checkbox" name="permits[{{$module->id}}]" value="1" disabled class="custom-control-input" id="{{$module->reference}}">
                                    <label class="custom-control-label" for="{{$module->reference}}">{{$module->name}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCreateRol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm " role="document">
        <form action="{{route('rol.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar rol</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="name" placeholder="Nombre del rol" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalEditRol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm " role="document">
        <form id="formUpdateRol" method="POST">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar rol</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="state" id="state" name="state" class="form-control" required>
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
@endpush
