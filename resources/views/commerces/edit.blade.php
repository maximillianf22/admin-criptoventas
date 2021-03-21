@extends('layouts.app', ['page' => 'Comercios'])

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
                            <li class="breadcrumb-item"><a href="{{route('commerces.index')}}">Comercios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar comercio</li>
                        </ol>
                        @if($data->state == 3)
                        <form action="{{route('commerce.activate')}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" value="{{$data->id}}" name="idCommerceToActivate">
                            <button type="submit" class="btn btn-neutral btnActivateCommerce">Activar comercio</button>
                        </form>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    <form method="post" action="{{route('commerces.update',[$data->id])}}" id="formUpdateCommerce" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row mt-5">
            <div class="col-4">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                @if(Storage::disk('public')->exists($data->getUser->photo))
                                <img class="profile-user-img rounded-circle img-fluid mx-auto d-block imgUpdate"
                                    src="{{ asset('storage/'.$data->getUser->photo) }}" alt="User profile picture">
                                @else
                                <img src="{{asset('assets/img/aliado.png')}}" class="imgUpdate rounded-circle">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pb-0">
                        <div class="d-flex justify-content-between"></div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="form-group">
                            <label class="form-control-label">Imagen</label>
                            <input type="file" name="profile_img" class="form-control inputImg">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Domicilio (Opcional)</label>
                            <input type="number" name="delivery_value" min="0" value="{{$data->delivery_value}}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Dirección del comercio *</label>
                            <input type="hidden" name="lat" id="newAddressLat"
                                value="{{ $data->getUser->getAddresses->count() > 0 ? $data->getUser->getAddresses->first()->lat : ''}}">
                            <input type="hidden" name="lng" id="newAddressLong"
                                value="{{ $data->getUser->getAddresses->count() > 0 ? $data->getUser->getAddresses->first()->lng : ''}}">
                            <input type="text" name="commerce_address" class="form-control" id="newAddress"
                                value="{{ $data->getUser->getAddresses->count() > 0 ? $data->getUser->getAddresses->first()->address : ''}}"
                                required>
                        </div>
                        <div class="form-group">
                            @if($data->state==3)
                            <label class="form-control-label" for="input-email">Estado *</label>
                            <select name="state" class="form-control" {{$data->state == 3 ? 'disabled' : ''}}>
                                <option value="3" {{$data->state == 3 ? 'selected' : ''}}>Pendiente por aprobar</option>
                                @foreach(Config::get('const.states') as $state => $value)
                                <option value="{{$state}}" {{$data->state == $state ? 'selected' : ''}}>{{$value}}
                                </option>
                                @endforeach
                            </select>
                            @endif
                            @if($data->state!=3)
                            <label class="form-control-label" for="input-email">Estado *</label>
                            <select name="state" class="form-control">
                                <option value="3" {{$data->state != 3 ? 'selected' : ''}}>Pendiente por aprobar</option>
                                @foreach(Config::get('const.states') as $state => $value)
                                <option value="{{$state}}" {{$data->state == $state ? 'selected' : ''}}>{{$value}}
                                </option>
                                @endforeach
                            </select>
                            @endif


                        </div>
                        <div class="form-group">
                            <label>Estado del comercio</label>
                            <select name="is_opened" class="form-control">
                                <option value="1" {{$data->is_opened == 1 ? 'selected' : ''}}>Abierto</option>
                                <option value="0" {{$data->is_opened == 0 ? 'selected' : ''}}>Cerrado</option>

                            </select>
                        </div>
                        {{--<div class="form-group">
                            <label>Acceso al administrador</label>
                            <select name="user_state" class="form-control">
                                <option value="1" {{$data->getUser->user_state == 1 ? 'selected' : ''}}>Sí</option>
                        <option value="0" {{$data->getUser->user_state == 0 ? 'selected' : ''}}>No</option>
                        </select>
                    </div>--}}
                    <div class="form-group">
                        <label class="form-control-label">Categorías</label>
                        <div class="row">
                            @foreach($categories as $category)
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    @if($data->getCategories->contains('id', $category->id))
                                    <input type="checkbox" name="categories[]" checked value="{{$category->id}}"
                                        class="custom-control-input" id="cat_{{$category->id}}">
                                    @else
                                    <input type="checkbox" name="categories[]" value="{{$category->id}}"
                                        class="custom-control-input" id="cat_{{$category->id}}">
                                    @endif
                                    <label class="custom-control-label"
                                        for="cat_{{$category->id}}">{{$category->name}}</label>
                                </div>
                            </div>
                            @endforeach
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
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">Actualizar comercio</h3>
                    </div>
                </div>
                <div class="card-body">

                    <h6 class="heading-small text-muted mb-4">Datos de contacto</h6>
                    <div class="">
                        <div class="row">
                            <div class="form-group col">
                                <label class="form-control-label">Nombre *</label>
                                <input type="text" name="name" class="form-control form-control-alternative"
                                    value="{{$data->getUser->name}}" placeholder="Nombre" required>
                            </div>
                            <div class="form-group col">
                                <label class="form-control-label">Apellidos *</label>
                                <input type="text" name="last_name" class="form-control form-control-alternative"
                                    value="{{$data->getUser->last_name}}" placeholder="Apellidos" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label class="form-control-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-alternative"
                                    value="{{$data->getUser->email}}" placeholder="Correo electrónico">
                            </div>
                            <div class="form-group col">
                                <label class="form-control-label">Celular *</label>
                                <input type="number" name="cellphone" class="form-control form-control-alternative"
                                    value="{{$data->getUser->cellphone}}" placeholder="Celular" required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="heading-small text-muted mb-4">Datos del comercio</h6>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-control-label" for="input-email">Nombre del Comercio*</label>
                            <input type="text" name="bussiness_name" value="{{$data->bussiness_name}}"
                                class="form-control form-control-alternative" placeholder="Nombre del comercio">
                        </div>
                        <div class="form-group col">
                            <label class="form-control-label" for="input-email">NIT *</label>
                            <input type="number" name="nit" value="{{$data->nit}}"
                                class="form-control form-control-alternative" placeholder="NIT" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-control-label" for="input-email">Configuración DomiciliosApp*</label>
                            <select name="delivery_config" class="form-control" required>
                                <option value="" selected disabled>Seleccione un tipo de domicilio</option>
                                @foreach($DeliveryConfig as $type)
                                <option value="{{$type->id}}" {{$data->delivery_config == $type->id ? 'selected' : ''}}>
                                    {{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label class="form-control-label" for="input-email">Tipo de comercio *</label>
                            <select name="commerce_type_vp" class="form-control" required disabled>
                                <option value="" selected disabled>Seleccione un tipo de comercio</option>
                                @foreach($commerce_type as $type)
                                <option value="{{$type->id}}"
                                    {{$data->commerce_type_vp == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="form-group col">
                            <label class="form-control-label">Contraseña</label>
                            <input type="text" name="password" readonly
                                class="form-control form-control-alternative inputEditPassword"
                                placeholder="Contraseña">
                        </div>
                        <div class="form-group col">
                            <label class="form-control-label">Generar nueva contraseña</label>
                            <button type="button" class="btn btn-info btn-block btnGenNewPassword">Generar nueva
                                contraseña</button>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-info mt-4 btnUpdatecommerce">Actualizar
                            comercio</button>
                    </div>
                </div>
            </div>
        </div>
</div>

</form>
</div>
@endsection

@push('js')
<script>
function initMap() {
    var defaultBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(4.511299, -74.170789),
        new google.maps.LatLng(4.769863, -74.024819)
    );

    autocompleteAddress = new google.maps.places.Autocomplete(
        document.getElementById('newAddress'), {
            bounds: defaultBounds,
            fields: ['geometry'],
        }
    );
    autocompleteAddress.addListener('place_changed', function() {
        let place = autocompleteAddress.getPlace()
        $('#newAddressLat').val(place.geometry.location.lat)
        $('#newAddressLong').val(place.geometry.location.lng)
    });
}
</script>
@endpush
