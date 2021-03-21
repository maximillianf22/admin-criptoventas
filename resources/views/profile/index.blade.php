@extends('layouts.app', ['page' => 'Perfiles'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i
                                        class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{route('profile.index')}}">Perfil</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-5">
    <form method="post" action="{{route('profile.update', [$user->id])}}" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row mt-5">
            <div class="col-4">
                <div class="card card-profile shadow mb-3">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                @if(Storage::disk('public')->exists($user->photo))
                                <img class="profile-user-img rounded-circle img-fluid mx-auto d-block imgUpdate"
                                    src="{{ asset('storage/'.$user->photo) }}" alt="User profile picture">
                                @else
                                <img class="profile-user-img rounded-circle img-fluid mx-auto d-block imgUpdate"
                                    src="https://www.aalforum.eu/wp-content/uploads/2016/04/profile-placeholder.png"
                                    alt="User profile picture">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4"></div>
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
                @if (Session::has('wrong'))
                <div class="alert alert-danger">
                    {{ session('wrong') }}
                </div>
                @endif
                @if (Session::has('password'))
                <div class="alert alert-danger">
                    {{ session('password') }}
                </div>
                @endif
                @if (Session::has('success'))
                <div class="alert alert-info">
                    {{ session('success') }}
                </div>
                @endif
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Actualizar Perfil</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Datos del usuario</h6>
                        <div class="">
                            <div class="row">
                                <input type="hidden" name="id" value="{{$user->id}}">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Tipo de documento *</label>
                                    <select name="document_type_vp" class="form-control" required>
                                        <option value="" selected disabled>Seleccione un tipo de documento</option>
                                        @foreach($types as $type)
                                        <option value="{{$type->id}}"
                                            {{$user->document_type_vp == $type->id ? 'selected' : ''}}>
                                            {{$type->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col focused">
                                    <label class="form-control-label">Documento *</label>
                                    <input type="text" name="document" class="form-control form-control-alternative"
                                        value="{{$user->document}}" placeholder="Documento" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label">Nombre *</label>
                                    <input type="text" name="name" class="form-control form-control-alternative"
                                        value="{{$user->name}}" placeholder="Nombre" required>
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label">Apellidos *</label>
                                    <input type="text" name="last_name" class="form-control form-control-alternative"
                                        value="{{$user->last_name}}" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label">Email</label>
                                    <input type="email" name="email" class="form-control form-control-alternative"
                                        value="{{$user->email}}" placeholder="Correo electrónico">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label">Celular *</label>
                                    <input type="number" name="cellphone" class="form-control form-control-alternative"
                                        value="{{$user->cellphone}}" placeholder="Celular" required>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="form-group col">
                                    <label class="form-control-label">Nueva Contraseña</label>
                                    <input type="password" name="password"
                                        class="form-control form-control-alternative inputEditPassword"
                                        placeholder="Nueva Contraseña" value="">
                                </div>
                                <div class="form-group col">
                                    <label>Confirmar contraseña</label>
                                    <input type="password" name="password2"
                                        class="form-control form-control-alternative inputEditPassword"
                                        placeholder="Confirmar Contraseña" value="">

                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4">Guardar</button>
                        </div>
                    </div>

                </div>
    </form>
</div>

<!-- Modal -->

@endsection
