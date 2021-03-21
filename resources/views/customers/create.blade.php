@extends('layouts.app', ['page' => 'Clientes'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('custommers.index')}}">Clientes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    <form method="post" action="{{route('custommers.store')}}" autocomplete="off">
        @csrf
        <div class="row mt-5 mb-3">
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
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card p-3 mt-3">
                    <div class="form-group mb-3">
                        <label>Buscar distribuidor (Opcional)</label>
                        <select name="distributor_id" id="distributorSearcher" class="form-control">
                            <option value="0" {{is_null(old('distributor_id')) ? 'selected' : ''}}>Ninguno</option>
                            @foreach($distributors as $distributor)
                            <option value="{{$distributor->id}}" {{old('distributor_id') == $distributor->id ? 'selected' : ''}}>{{$distributor->getUser->name}} {{$distributor->getUser->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="profile_id" class="form-control">
                            <option value="" disabled {{is_null(old('rol_id')) ? 'selected' : ''}}>Seleccione un profile</option>
                            <option value="3" {{old('profile_id') == 3 ? 'selected' : ''}}>Cliente</option>
                            <option value="5" {{old('profile_id') == 5 ? 'selected' : ''}}>Mayorista</option>
                        </select>
                    </div>
                    <div class="media distributorContainer" style="display: none;">
                        <img src="https://immedilet-invest.com/wp-content/uploads/2016/01/user-placeholder.jpg" width="50" class="mr-3" alt="...">
                        <div class="media-body">
                            <h5 class="mt-0 mb-0"></h5>
                            <small></small>
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
                                    <input type="password" name="password_confirmation" id="input-email" class="form-control form-control-alternative" placeholder="Confirmar contraseña" required>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Codigo Distribuidor</label>
                                    <input type="text" name="distributor_code" id="input-email" class="form-control form-control-alternative distributor_code" placeholder="Codigo del Distribuidor">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">comision</label>
                                    <input type="number" name="distributor_percent" id="input-email" class="form-control form-control-alternative distributor_percent" placeholder="Comision del Distribuidor">
                                </div>
                            </div> -->
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