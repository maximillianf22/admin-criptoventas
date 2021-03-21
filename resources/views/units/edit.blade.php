@extends('layouts.app', ['page' => 'Unidades'])

@section('content')
<div class="header bg-gradient-info pb-7 pt-5 pt-md-7">
  <div class="container-fluid">
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
  @isset($success)
  <div class="alert alert-succes">
    {{ $success}}
  </div>
  @endisset
  @if (Session::has('success'))
  <div class="alert alert-info">
    creado con exito
  </div>
  @endif
</div>
<div class="row mt-5">
  <div class="col-2">
    <div class="card card-profile shadow">
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
  </div>
  <div class="col-10">
    <div class="card bg-secondary shadow">
      <div class="card-header bg-white border-0">
        <div class="row align-items-center">
          <h3 class="mb-0">Actualizar unidades</h3>
        </div>
      </div>
      <div class="card-body">
        <form method="post" action="{{route('UnitsController.update',[$data->id])}}" autocomplete="off">
          @csrf
          @method('put')
          <div class="pl-lg-4">
            <div class="row">
              <div class="form-group col">
                <label class="form-control-label" for="input-email">Id comercio: {{data->commerce_id}}</label>
              </div>
              <div class="form-group col">
                <label class="form-control-label" for="input-email">Nombre: </label>
                <input type="number" name="nit" value="{{$data->name}}" class="form-control form-control-alternative" placeholder="Name..." required>
              </div>
              <div class="form-group col">
                <label class="form-control-label" for="input-email">Estado: </label>
                <input type="number" name="nit" value="{{$data->state}}" class="form-control form-control-alternative" placeholder="Estado..." required>
              </div>
            </div>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-info mt-4">Actualizar</button>
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