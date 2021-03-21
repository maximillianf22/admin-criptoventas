@extends('layouts.app', ['page' => 'Usuarios'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5 ">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Usuarios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear usuario</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        - {{$error}} <br>
        @endforeach
    </div>
    @endif
    <form method="post" action="{{route('users.store')}}" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="row mt-5 mb-5">
            <div class="col-4">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="https://immedilet-invest.com/wp-content/uploads/2016/01/user-placeholder.jpg" class="rounded-circle imgUpdate">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Foto</label>
                            <input type="file" name="profileImg" class="form-control inputImg">
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-control-label">Rol *</label>
                            <select name="rol_id" class="form-control" required>
                                <option value="" selected disabled>Seleccione un rol</option>
                                @foreach($roles as $rol)
                                <option value="{{$rol->id}}" {{old('rol_id') == $rol->id ? 'selected' : ''}}>{{$rol->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Crear usuario</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Datos del usuario</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Tipo de documento *</label>
                                    <select name="document_type_vp" class="form-control" required>
                                        <option value="" selected disabled>Seleccione un tipo de documento</option>
                                        @foreach($types as $type)
                                        <option value="{{$type->id}}" {{old('document_type_vp') == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Documento *</label>
                                    <input type="text" name="document" id="input-name" class="form-control form-control-alternative" placeholder="Documento" value="{{old('document')}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Nombre *</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative" placeholder="Nombre" value="{{old('name')}}" required>
                                </div>
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Apellidos *</label>
                                    <input type="text" name="last_name" id="input-name" class="form-control form-control-alternative" placeholder="Apellidos" value="{{old('last_name')}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Email</label>
                                    <input type="email" name="email" id="input-email" class="form-control form-control-alternative" value="{{old('email')}}" placeholder="Correo electrónico">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Celular *</label>
                                    <input type="number" name="cellphone" id="input-email" class="form-control form-control-alternative" value="{{old('cellphone')}}" placeholder="Celular" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Contraseña *</label>
                                    <input type="password" name="password" id="input-email" class="form-control form-control-alternative" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Confirmar contraseña *</label>
                                    <input type="password" name="confirm_password" id="input-email" class="form-control form-control-alternative" placeholder="Confirmar contraseña" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')

@endpush