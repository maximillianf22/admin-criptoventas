@extends('layouts.app', ['page' => 'Parametros'])

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
              <h3 class="mb-0"></h3>
            </div>
            <div class="col text-right">
              <a href="{{route('parameter_values.create')}}" class="btn btn-sm btn-primary">Crear nuevo parametro</a>
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
                <th scope="col">Nombre</th>
                <th scope="col">Extra</th>
                <th scope="col">Estado</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="store-b-manager">
              @if (count($parameters)>0)
              @foreach ($parameters as $parameter)
              <tr class="" style="padding:1px; color:#000 !important ">
                <td class="" style="padding:10px !important; text-align:left; color:#000 !important">
                  Ref-{{$parameter->parameter_id}}
                </td>
                <td class="" style="padding:10px !important; text-align:left;color:#000 !important">
                  {{$parameter->name}}
                </td>
                <td class="text-center" style="padding:10px !important; color:#000 !important">
                  {{$parameter->extra}}
                </td>
                <td class="text-center" style="padding:10px !important;color:#000 !important">
                  {{$parameter->state}}
                </td>

                <td class="text-center" style="padding:10px !important; font-weight:200; font-size:11px">
                  <div class="jj-button-mini" href="{{route('parametersValues.edit', [$parameter->id])}}">Editar</div>
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="9" class="pad-all text-center">No se encontraron registros</td>
              </tr>
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