@extends('layouts.app', ['page' => 'Unidades'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('shipping.index')}}">Horas de entrega</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    <div class="row mt-5">
        <div class="col-4">
            <div class="card shadow bg-secondary">
                <div class="card-header">
                    <div class="row">
                        <h3 class="mb-0">Horas de entrega</h3>
                    </div>
                </div>
                <div class="card-body">
                    @if(Auth::user()->rol_id != 2)
                    <div class="form-group">
                        <label>Comercios</label>
                        <select class="form-control" id="commerce_id">
                            <option value="" selected default>Selecione un comercio</option>
                            @foreach($commerces as $commerce)
                            <option value="{{$commerce->id}}">{{$commerce->bussiness_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" id="commerce_id" value="{{Auth::user()->getCommerce->id}}">
                    @endif
                    <hr class="mt-2 mb-2">
                    <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="1">Lunes</button>
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="2">Martes</button>
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="3">Miercoles</button>
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="4">Jueves</button>
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="5">Viernes</button>
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="6">Sabado</button>
                        <button type="button" class="list-group-item list-group-item-action btnWeekDay" value="7">Domingo</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card shadow">
                <div class="card-header border-0 pl-4 pb-1">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Horarios disponibles</h3>
                        </div>
                    </div>
                </div>
                <div class="horarios">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCreateHora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form id="formCreateHora" action="{{route('shipping.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear horario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col">
                            <label>Hora inicial *</label>
                            <input type="time" name="hora_inicial" value="{{date('H:m')}}" class="form-control">
                        </div>
                        <div class="form-group col">
                            <label>Hora final *</label>
                            <input type="time" name="hora_final" value="{{date('H:m')}}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Limite de cupos *<small>(Este limite de cupos se reiniciará diariamente)</small></label>
                        <input type="number" name="limit" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalEditHora" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm " role="document">
        <form id="formEditHora" method="POST">
            @csrf
            @method('put')
            <input type="hidden" id="idHorario" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar hora de entrega</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col">
                            <label>Hora inicial *</label>
                            <input type="time" name="hora_inicial" id="hora_inicial" class="form-control">
                        </div>
                        <div class="form-group col">
                            <label>Hora final *</label>
                            <input type="time" name="hora_final" id="hora_final" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cupos restantes</label>
                        <input readonly type="number" name="limit" id="limite_cupo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Cupos diarios <small>(Este limite de cupos se reiniciará diariamente)</small></label>
                        <input type="number" name="limit_pd" id="limite_cupo_pd" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="state" id="state" class="form-control">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')

@endpush