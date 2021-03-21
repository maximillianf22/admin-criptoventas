@extends('layouts.app')

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('commerces.index')}}">Minimos de compra</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
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
    @if (Session::has('success'))
    <div class="alert alert-info">
        {{ session('success') }}
    </div>
    @endif
    <div class="row mt-5">
        <div class="col">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">Minimos de compra por perfil</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('minShoppingValue.store')}}" id="formUpdateMin" autocomplete="off" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Comercio</label>
                            <select name="commerce_id" class="form-control commerceMinShopping" required>
                                <option value="" selected disabled>Seleccione un comercio</option>
                                @foreach($commerce as $comm)
                                <option value="{{$comm->id}}">{{$comm->bussiness_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Perfil</th>
                                        <th>Mínimo de compra</th>
                                    </tr>
                                </thead>
                                <tbody class="store-b-manager">
                                    @foreach($perfiles as $perfil)
                                    <tr id="{{$perfil->id}}">
                                        <td>
                                            <p class="m-0 text-dark">{{$perfil->name}}</p>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control inputValue" name="minimos[{{$perfil->id}}]" value="{{$perfil->value}}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="enviar" class="btn btn-info mt-4 btnSave">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')




@endpush