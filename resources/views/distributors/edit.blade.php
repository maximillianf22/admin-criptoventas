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
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                        @if($data->getUser->code_confirmed == 0)
                        <form action="{{route('customer.activate')}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" value="{{$data->getUser->id}}" name="idCustomerToActivate">
                            <button type="submit" class="btn btn-danger">Confirmar distribuidor</button>
                        </form>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">

    <form method="post" action="{{route('distributors.update', [$data->id])}}" autocomplete="off">
        @csrf
        @method('put')
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
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5"></div>
                                <div class="form-group">
                                    <label class="form-control-label">Código de Distribuidor *</label>
                                    <input type="text" name="distributor_code" placeholder="Codigo" value="{{$data->distributor_code}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">% de Comisión *</label>
                                    <input type="number" name="distributor_percent" placeholder="Porcentaje" value="{{$data->distributor_percent}}" class="form-control">
                                </div>
                                <div class="form-group">
                            <label>Rol</label>
                            <select name="profile_id" class="form-control">
                                <option value="3" {{$data->getUser->rol_id == 3 ? 'selected' : ''}}>Cliente</option>
                                <option value="4" {{$data->getUser->rol_id == 4 ? 'selected' : ''}}>Distribuidor</option>
                            </select>
                        </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Estado *</label>
                                    <select name="state" class="form-control">
                                        @foreach(Config::get('const.user_states') as $state => $value)
                                        <option value="{{$state}}" {{$data->getUser->user_state == $state ? 'selected' : ''}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
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
                    {{session('success')}}
                </div>
                @endif
                @if (Session::has('failed'))
                <div class="alert alert-danger">
                    {{session('failed')}}
                </div>
                @endif
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Actualizar distribuidor</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Datos del distribuidor</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Tipo de documento *</label>
                                    <select name="document_type_vp" class="form-control" required>
                                        <option value="" selected disabled>Selecione tipo documento</option>
                                        @foreach($types as $type)
                                        @if($data->getUser->getDocType)
                                        <option value="{{$type->id}}" {{$type->id == $data->getUser->getDocType->id ? 'selected' : ''}}>{{$type->name}}</option>
                                        @else
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Documento *</label>
                                    <input type="text" name="document" value="{{$data->getUser->document}}" id="input-name" class="form-control form-control-alternative" placeholder="Documento" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Nombre *</label>
                                    <input type="text" value="{{$data->getUser->name}}" name="name" id="input-name" class="form-control form-control-alternative" placeholder="Nombre" required>
                                </div>
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Apellidos *</label>
                                    <input type="text" value="{{$data->getUser->last_name}}" name="last_name" id="input-name" class="form-control form-control-alternative" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Email (Opcional)</label>
                                    <input type="email" value="{{$data->getUser->email}}" name="email" id="input-email" class="form-control form-control-alternative" placeholder="Correo electrónico">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Celular *</label>
                                    <input type="number" value="{{$data->getUser->cellphone}}" name="cellphone" id="input-email" class="form-control form-control-alternative" placeholder="Celular" required>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="form-group col">
                                    <label class="form-control-label">Contraseña</label>
                                    <input type="text" name="password" readonly class="form-control form-control-alternative inputEditPassword" placeholder="Contraseña">
                                </div>
                                <div class="form-group col">
                                    <label>Generar nueva contraseña</label>
                                    <button type="button" class="btn btn-info btn-block btnGenNewPassword">Generar nueva contraseña</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4">Actualizar Distribuidor</button>
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