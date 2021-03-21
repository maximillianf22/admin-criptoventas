@extends('layouts.app', ['page' => 'Clientes'])

@section('css')
<style>
    .pac-container {
        z-index: 10000 !important;
    }
</style>
@endsection

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
                        @if($data->getUser->code_confirmed == 0)
                        <form action="{{route('customer.activate')}}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" value="{{$data->getUser->id}}" name="idCustomerToActivate">
                            <button type="submit" class="btn btn-danger">Confirmar cliente</button>
                        </form>
                        @endif
                    </nav>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    <form method="post" action="{{route('custommers.update', [$data->id])}}" autocomplete="off">
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
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="media">
                            <img src="https://immedilet-invest.com/wp-content/uploads/2016/01/user-placeholder.jpg" width="50" class="mr-3" alt="...">
                            <div class="media-body">
                                <h5 class="mt-0 mb-0">{{is_null($data->distributor_id) ? 'No tiene' : $data->getDistributor->getUser->name . ' ' . $data->getDistributor->getUser->last_name}}</h5>
                                <small>{{is_null($data->distributor_id) ? 'No tiene' : 'CODIGO: ' . $data->getDistributor->distributor_code}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Buscar distribuidor (Opcional)</label>
                            <select name="distributor_id" id="distributorSearcher" class="form-control">
                                <option value="0">Ninguno</option>
                                @foreach($distributors as $distributor)
                                <option value="{{$distributor->id}}" {{$data->distributor_id == $distributor->id ? 'selected' : ''}}>{{$distributor->getUser->name}} {{$distributor->getUser->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Rol</label>
                            <select name="profile_id" class="form-control">
                                <option value="3" {{$data->getUser->rol_id == 3 ? 'selected' : ''}}>Cliente</option>
                                <option value="5" {{$data->getUser->rol_id == 5 ? 'selected' : ''}}>Mayorista</option>
                                <option value="4" {{$data->getUser->rol_id == 4 ? 'selected' : ''}}>Distribuidor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-email">Estado *</label>
                            <select name="user_state" class="form-control">
                                @foreach(Config::get('const.user_states') as $state => $value)
                                <option value="{{$state}}" {{$data->getUser->user_state == $state ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card shadow mt-3">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="row">
                            <div class="col">
                                <h3>Direcciones</h3>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-info btn-sm btnAddDirection">Agregar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2 mb-2" style=" overflow: auto;">
                        @if($data->getUser->getAddresses)
                        @foreach($data->getUser->getAddresses as $address)
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">{{$address->name}}</h5>
                                        <span class="font-weight-bold mb-0">{{$address->address}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="btn btn-sm bg-warning text-white rounded-circle btnEditAddress shadow mt-2" data-address-id="{{$address->id}}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </div>
                                        <br>
                                        <div class="btn btn-sm bg-danger text-white rounded-circle btnDeleteAddress shadow mt-2" data-address-id="{{$address->id}}">
                                            <i class="fas fa-trash-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
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
                            <h3 class="mb-0">Actualizar cliente</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">Datos del cliente</h6>
                        <div class="pl-lg-4">
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
                            <button type="submit" class="btn btn-info mt-4">Actualizar Cliente</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="modalCreateDirection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{route('addresses.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear una dirección</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="{{$data->getUser->id}}">
                        <input type="hidden" id="newAddressLat" name="lat">
                        <input type="hidden" id="newAddressLong" name="lng">
                        <label>Nueva dirección *</label>
                        <input type="text" placeholder="Direccion" required name="address" id="newAddress" class="form-control">
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label>Nombre de la dirección *</label>
                            <input type="text" class="form-control" required name="name" id="nameNewAddress" placeholder="Nombre">
                        </div>
                        <div class="form-group col">
                            <label>Descripción *</label>
                            <input type="text" class="form-control" required name="observation" id="descNewAddress" placeholder="Descripción">
                        </div>
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
<div class="modal fade" id="modalEditDirection" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="formEditAddress" method="POST">
            @csrf
            @method('put')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar una dirección</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="user_id" id="editUserID">
                        <input type="hidden" id="editAddressLat" name="lat">
                        <input type="hidden" id="editAddressLng" name="lng">
                        <label>Nueva dirección *</label>
                        <input type="text" placeholder="Direccion" required name="address" id="editAddress" class="form-control">
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label>Nombre de la dirección *</label>
                            <input type="text" class="form-control" required name="name" id="nameEditAddress" placeholder="Nombre">
                        </div>
                        <div class="form-group col">
                            <label>Descripción *</label>
                            <input type="text" class="form-control" required name="observation" id="descEditAddress" placeholder="Descripción">
                        </div>
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

        autocompleteAddressEdit = new google.maps.places.Autocomplete(
            document.getElementById('editAddress'), {
                bounds: defaultBounds,
                fields: ['geometry'],
            }
        );
        autocompleteAddressEdit.addListener('place_changed', function() {
            let place = autocompleteAddressEdit.getPlace()
            $('#editAddressLat').val(place.geometry.location.lat)
            $('#editAddressLng').val(place.geometry.location.lng)
        });
    }
</script>
@endpush