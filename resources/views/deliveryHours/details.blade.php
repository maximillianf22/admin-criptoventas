<div class="container pb-3 pl-4 pt-0">

    <div class="row">
        <div class="col">
            <h5 class="text-muted">Horarios de los {{Config::get('const.weekDay')[$day]}}</span></h5>
        </div>
        <div class="col text-right">
            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalCreateHora">Crear horario</button>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="result" class="table align-items-center table-flush text-center">
        <thead class="thead-light text-center">
            <tr>
                <th>Hora Inicial</th>
                <th>Hora Final </th>
                <th>Cupos restantes</th>
                <th>Cupos por día</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="text-center">
            @if ($hours->count() > 0)
            @foreach ($hours as $hour)
            <tr id="{{$hour->id}}">
                <td>
                    {{$hour->init_hour}}
                </td>
                <td>
                    {{$hour->fin_hour}}
                </td>
                <td>
                    {{$hour->limit == -1 ? 'ilimitado' : $hour->limit}}
                </td>
                <td>
                    {{$hour->limit_pd == -1 ? 'ilimitado' : $hour->limit_pd}}
                </td>
                <td>{{Config::get('const.states')[$hour->state]}}</td>
                <td>
                    <button class="btn btn-sm btn-warning btnEditHorario"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger btnEraseHorario"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>
            @endforeach
            @else
            <tr class="text-center">
                <td colspan="5">No hay horarios creados</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>