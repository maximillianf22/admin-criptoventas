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
                            <li class="breadcrumb-item active" aria-current="page">Crear comercio</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--7 mb-3">
    <form method="post" action="{{route('commerces.store')}}" id="formUpdateCommerce" autocomplete="off"
        enctype="multipart/form-data">
        @csrf
        <div class="row mt-5">
            <div class="col-4">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{asset('assets/img/aliado.png')}}" class="imgUpdate rounded-circle">
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
                            <label>Foto</label>
                            <input type="file" name="img_profile" class="form-control inputImg" required>
                        </div>
                        <div class="form-group">
                            <label>Estado del comercio</label>
                            <select name="is_opened" class="form-control" required>
                                <option value="" disabled {{is_null(old('is_opened')) ? 'selected' : ''}}>Abierto o
                                    cerrado</option>
                                <option value="1" {{old('is_opened') == "1" ? 'selected' : ''}}>Abierto</option>
                                <option value="0" {{old('is_opened') == "0" ? 'selected' : ''}}>Cerrado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Domicilio (Opcional)</label>
                            <input type="number" name="delivery_value" min="0" value="0" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Dirección del comercio *</label>
                            <input type="hidden" name="lat" id="newAddressLat">
                            <input type="hidden" name="lng" id="newAddressLong">
                            <input type="text" name="commerce_address" class="form-control" id="newAddress" required>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 categoriesContainer" style="display: none;">
                    <div class="card-body">
                        <label class="mt-0 mb-0">Categorias (Min. 1) *</label><br>
                        <div class="row mt-2 categoriesList">

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
                    {{ session('success') }}
                </div>
                @endif
                @if (Session::has('failed'))
                <div class="alert alert-info">
                    {{ session('failed') }}
                </div>
                @endif
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Crear comercio</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Datos del usuario</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Nombre *</label>
                                    <input type="text" name="name" value="{{old('name')}}" id="input-name"
                                        class="form-control form-control-alternative" placeholder="Nombre" required>
                                </div>
                                <div class="form-group col focused">
                                    <label class="form-control-label" for="input-name">Apellidos *</label>
                                    <input type="text" name="last_name" value="{{old('last_name')}}" id="input-name"
                                        class="form-control form-control-alternative" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Nombre del Comercio*</label>
                                    <input type="text" name="bussiness_name" value="{{old('bussiness_name')}}"
                                        class="form-control form-control-alternative" placeholder="Nombre del comercio">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">NIT *</label>
                                    <input type="number" name="nit" value="{{old('nit')}}"
                                        class="form-control form-control-alternative" placeholder="NIT" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Email</label>
                                    <input type="email" name="email" value="{{old('email')}}" id="input-email"
                                        class="form-control form-control-alternative" placeholder="Correo electrónico">
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Celular *</label>
                                    <input type="number" name="cellphone" value="{{old('cellphone')}}" id="input-email"
                                        class="form-control form-control-alternative" placeholder="Celular" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Configuración de
                                        DomciliosApp*</label>
                                    <select name="delivery_config" class="form-control" required>
                                        <option value="" disabled {{is_null(old('delivery_config')) ? 'selected' : ''}}>
                                            Seleccione un tipo de domicilio</option>
                                        @foreach($DeliveryConfig as $type)
                                        <option value="{{$type->id}}"
                                            {{old('delivery_config') == $type->id ? 'selected' : ''}}>{{$type->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Tipo de comercio *</label>
                                    <select name="commerce_type_vp" class="form-control selectCommerceType" required>
                                        <option value="" disabled
                                            {{is_null(old('commerce_type_vp')) ? 'selected' : ''}}>Seleccione un tipo de
                                            comercio</option>
                                        @foreach($commerce_type as $type)
                                        <option value="{{$type->id}}"
                                            {{old('commerce_type_vp') == $type->id ? 'selected' : ''}}>{{$type->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Contraseña *</label>
                                    <input type="password" name="password" id="input-email"
                                        class="form-control form-control-alternative" placeholder="Contraseña" required>
                                </div>
                                <div class="form-group col">
                                    <label class="form-control-label" for="input-email">Confirmar contraseña *</label>
                                    <input type="password" name="password_confirmation" id="input-email"
                                        class="form-control form-control-alternative" placeholder="Confirmar contraseña"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4 btnUpdatecommerce">Crear comercio</button>
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
