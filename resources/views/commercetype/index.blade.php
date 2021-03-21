@extends('layouts.app')

@section('content')
<div class="header bg-gradient-info pb-7 pt-5 pt-md-7">
    <div class="container-fluid">
    </div>
</div>

<div class="container-fluid mt--7">

    <div class="row mt-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Tipos de comercio</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{route('commercestypes.create')}}" class="btn btn-sm btn-primary">Crear tipo comercio</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th scope="col">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Extra</th>
                                <th scope="col">Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($parameters->count())
                            @foreach($parameters as $parameter)
                            <tr>
                                <td>{{$parameter->parameter_id}}</td>
                                <td>{{$parameter->name}}</td>
                                <td>{{$parameter->extra}}</td>
                                <td>{{$parameter->state}}</td>
                                <td>
                                    <a class="btn bnt-warning" href="{{route('commercestype.edit', [$parameter->id])}}"><i class="fas fa-edit">Editar</i></a>
                                    <a class="btn btn-sm btn-info" href="{{route('commercestype.category', [$parameter->id])}}"><i class="fas fa-list"></i></a>
                                    <button class="btn bnt-danger"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush