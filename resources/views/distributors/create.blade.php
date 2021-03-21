@extends('layouts.app', ['page' => 'Distribuidores'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('distributors.index')}}">Distribuidores</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        - {{$error}} <br>
        @endforeach
    </div>
    @endif
    <form method="post" action="{{route('distributors.store')}}" autocomplete="off">
        @csrf
        <div class="row mt-5">
            <div class="col-4">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="https://immedilet-invest.com/wp-content/uploads/2016/01/user-placeholder.jpg" class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="card-profile-stats mt-md-5">

                        </div>
                        <div class="form-group">
                            <label>% de Comisión</label>
                            <input type="number" name="distributor_percent" placeholder="Porcentaje" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Crear cliente</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Datos del cliente</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Tipo de documento *</label>
                                    <select name="document_type_vp" class="form-control" required>
                                        <option value="" selected disabled>Selecione tipo documento</option>
                                        @foreach($types as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Documento *</label>
                                    <input type="text" name="document" id="input-name" value="{{old('document')}}" class="form-control form-control-alternative" placeholder="Documento" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Nombre *</label>
                                    <input type="text" name="name" id="input-name" value="{{old('name')}}" class="form-control form-control-alternative" placeholder="Nombre" required>
                                </div>
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Apellidos *</label>
                                    <input type="text" name="last_name" id="input-name" value="{{old('last_name')}}" class="form-control form-control-alternative" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Email (Opcional)</label>
                                    <input type="email" name="email" id="input-email" value="{{old('email')}}" class="form-control form-control-alternative" placeholder="Correo electrónico">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Celular *</label>
                                    <input type="number" name="cellphone" id="input-email" value="{{old('cellphone')}}" class="form-control form-control-alternative" placeholder="Celular" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Contraseña *</label>
                                    <input type="password" name="password" id="input-email" class="form-control form-control-alternative" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Confirmar contraseña *</label>
                                    <input type="password" name="password_confirmed" id="input-email" class="form-control form-control-alternative" placeholder="Confirmar contraseña" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4">Crear Cliente</button>
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