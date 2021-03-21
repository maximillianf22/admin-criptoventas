@extends('layouts.app', ['page' => 'Dashboard'])

@section('content')
    <div class="header bg-gradient-info pb-3 pt-5">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('administrator.home') }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Dashboard</a></li>
                                <!-- <li class="breadcrumb-item active" aria-current="page"></li> -->
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 mb-2">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <input type="hidden" class="tipoCount" value="0" name="">
                                        <h2 class="card-title text-uppercase text-muted mb-0">Total de pedidos</h2>
                                        <span class="h3 font-weight-bold mb-0 countDisplay">{{ $orderCount }} Pedidos |$
                                            {{ number_format($orderTotal) }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-cart"></i>
                                        </div>
                                    </div>
                                    </div>
                                <p class="mb-0 text-sm">
                                    <span class="text-nowrap datesFilter">Inicio - Hoy</span>
                                    <span class="text-nowrap" style="display:none">dd/mm/yyyy - dd/mm/yyyy</span>
                                </p>
                            </div>
                            <div class="card-body pb-2 pt-2">
                                <div class="row align-items-center">
                                    <div class="form-group col-12 pr-1 pl-1 mb-2">
                                        <label for="">Comercio:</label>
                                        <select class="form-control commerce">
                                            @if (Auth::user()->rol_id == 1)
                                                <option selected value="0">todos</option>
                                                @foreach ($commerce as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['bussiness_name'] }}</option>
                                                @endforeach
                                            @else
                                                @if (Auth::user()->rol_id == 2)
                                                    <option value="{{ Auth::user()->getCommerce->id }}">
                                                        {{ Auth::user()->getCommerce->bussiness_name }}</option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-12  pr-1 pl-1 mb-0">
                                        <label for="">Desde</label>
                                        <input type="date" class="form-control startDate" value="{{ date('Y-m-d') }}"
                                            placeholder="Inicial">
                                    </div>
                                    <div class="form-group col-12  pr-1 pl-1 mb-0">
                                        <label for="">Hasta</label>
                                        <input type="date" class="form-control endDate" value="{{ date('Y-m-d') }}"
                                            placeholder="Final">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-2">
                                <span class="text-danger errorDisplay"></span>
                                <button class="btn btn-info btn-block btn-count-date">Filtrar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-8 mb-2">
                        <form action="{{ route('reportes') }}" method="post" target="blank">
                            @csrf
                            <input type="hidden" value="1" name="type">
                            <div class="card card-stats" data-type="1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Ventas por Categorias</h5>
                                            <span class="h2 font-weight-bold mb-0 countDisplay">{{ $usersCount }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="ni ni-money-coins"></i>

                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-sm">
                                        <span class="text-nowrap datesFilter">Inicio - Hoy</span>
                                        <span class="text-nowrap" style="display:none">dd/mm/yyyy - dd/mm/yyyy</span>
                                    </p>
                                </div>
                                <div class="card-body pb-2 pt-2">

                                    <div class="row align-items-center">
                                        <div class="form-group col-6 pr-1 pl-1 mb-2">
                                            <label for="">Comercio:</label>
                                            <select class="form-control commerce" name="commerce" id="selectCommerce">

                                                @if (Auth::user()->rol_id == 1)
                                                    <option selected value="0">todos</option>
                                                    @foreach ($commerce as $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['bussiness_name'] }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @if (Auth::user()->rol_id == 2)
                                                        <option value="{{ Auth::user()->getCommerce->id }}" selected>
                                                            {{ Auth::user()->getCommerce->bussiness_name }}</option>
                                                    @endif
                                                @endif

                                            </select>
                                        </div>
                                        <div class="form-group col-6 pr-1 pl-1 mb-2">
                                            <label for="">Categoría:</label>
                                            <select class="form-control" name="category" aria-placeholder="Categorias"
                                                id="categories" required>


                                            </select>
                                        </div>

                                    </div>
                                    <div class="row align-items-center">
                                        <div class="form-group col-12  pr-1 pl-1 mb-0">
                                            <label for="">Desde</label>
                                            <input type="date" name="start" class="form-control startDate"
                                                value="{{ date('Y-m-d') }}" placeholder="Inicial">
                                        </div>
                                        <div class="form-group col-12  pr-1 pl-1 mb-0">
                                            <label for="">Hasta</label>
                                            <input type="date" name="end" class="form-control endDate"
                                                value="{{ date('Y-m-d') }}" placeholder="Final">
                                        </div>
                                    </div>
                                </div>
                                <!-- Card body -->
                                <div class="card-footer p-2">
                                    <span class="text-danger errorDisplay"></span>
                                    <button type="submit" class="btn btn-info btn-block">Generar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-4 mb-2">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">ventas por comercio</h5>
                                        <span class="h2 font-weight-bold mb-0 "></span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-shop"></i>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <form action="{{ route('reportes') }}" method="post" target="blank">
                                @csrf
                                <input type="hidden" name="type" value="0">
                                <div class="card-body pb-2 pt-2">
                                    <div class="row align-items-center">
                                        <div class="form-group col-12 pr-1 pl-1 mb-2">
                                            <label for="">Comercio:</label>
                                            <select name="commerce" id="" class="form-control">
                                                @if (Auth::user()->rol_id == 1)
                                                    <option selected value="0">todos</option>
                                                    @foreach ($commerce as $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['bussiness_name'] }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @if (Auth::user()->rol_id == 2)
                                                        <option value="{{ Auth::user()->getCommerce->id }}" selected>
                                                            {{ Auth::user()->getCommerce->bussiness_name }}</option>
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-12  pr-1 pl-1 mb-0">
                                            <label for="">Desde</label>
                                            <input type="date" class="form-control startDate" name="start"
                                                value="{{ date('Y-m-d') }}" placeholder="Inicial">
                                        </div>
                                        <div class="form-group col-12  pr-1 pl-1 mb-0">
                                            <label for="">Hasta</label>
                                            <input type="date" class="form-control endDate" name="end"
                                                value="{{ date('Y-m-d') }}" placeholder="Final">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer p-2">
                                    <button type="submit" class="btn btn-info btn-block">Generar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-4 mb-2">
                        <div class="card card-stats" data-type="1">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col my-0">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Usuarios #:
                                            <span class="font-weight-bold mb-0 countDisplay">{{ $usersCount }}</span>
                                            <p class="mb-0 text-sm">
                                                <span class="text-nowrap datesFilter">Inicio - Hoy</span>
                                                <span class="text-nowrap" style="display:none">dd/mm/yyyy -
                                                    dd/mm/yyyy</span>
                                            </p>
                                        </h5>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                            <i class="ni ni-single-02"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body pb-2 pt-2">
                                <div class="row align-items-center">
                                    <div class="form-group col-12 pr-1 pl-1 mb-2 focused">
                                        <label for="">Tipo de usuario:</label>
                                        <select class="form-control tipoCount">
                                            <option value="1">todos</option>
                                            <option value="2">Comercios</option>
                                            <option value="3">Distribuidores</option>
                                            <option value="4">clientes</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="form-group col-12  pr-1 pl-1 mb-0">
                                        <label for="">Desde</label>
                                        <input type="date" class="form-control startDate" value="{{ date('Y-m-d') }}"
                                            placeholder="Inicial">
                                    </div>
                                    <div class="form-group col-12  pr-1 pl-1 mb-0">
                                        <label for="">Hasta</label>
                                        <input type="date" class="form-control endDate" value="{{ date('Y-m-d') }}"
                                            placeholder="Final">
                                    </div>
                                </div>
                            </div>
                            <!-- Card body -->
                            <div class="card-footer p-2">
                                <span class="text-danger errorDisplay"></span>
                                <button class="btn btn-info btn-block btn-count-date">Filtrar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 mb-2">
                        <form action="{{ route('reportes') }}" method="post" target="blank">
                            @csrf
                            <input type="hidden" value="2" name="type">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col my-0">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Ventas por distribuidor
                                            </h5>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="ni ni-badge"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pb-2 pt-2">
                                    <div class="row align-items-center">
                                        <div class="form-group col-12 pr-1 pl-1 mb-2">
                                            <label for="">Comercio:</label>
                                            <select name="commerce" id="" class="form-control">
                                                @if (Auth::user()->rol_id == 1)
                                                    <option selected value="0">todos</option>
                                                    @foreach ($commerce as $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['bussiness_name'] }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @if (Auth::user()->rol_id == 2)
                                                        <option value="{{ Auth::user()->getCommerce->id }}" selected>
                                                            {{ Auth::user()->getCommerce->bussiness_name }}</option>
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-12  pr-1 pl-1 mb-0">
                                            <label for="">Desde</label>
                                            <input name="start" type="date" class="form-control startDate"
                                                value="{{ date('Y-m-d') }}" placeholder="Inicial">
                                        </div>
                                        <div class="form-group col-12  pr-1 pl-1 mb-0">
                                            <label for="">Hasta</label>
                                            <input name="end" type="date" class="form-control endDate"
                                                value="{{ date('Y-m-d') }}" placeholder="Final">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer p-2">
                                    <button type="submit" class="btn btn-info btn-block">Generar</button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')

    <script>
        let idCarga = +$('#selectCommerce').val()

        fetch('/administrator/categories/showByCommerce2?id=' + idCarga, {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                $('#categories').empty();
                data.data.forEach(element => {

                    $('#categories').append(
                        `<option value="${element.id}">${element.name}</option>`)
                });
            })

        $('.btn-count-date').click(function() {
            let parent = $(this).parent().parent();
            let startD = parent.find('.startDate').val();
            let endD = parent.find('.endDate').val();
            let commerce = +parent.find('.commerce').val()
            let type = parent.find('.tipoCount').val();
            let data = {
                commerce: commerce,
                start: startD,
                end: endD,
                type: type
            }
            fetch('/countDashboard', {
                    method: 'post',
                    body: JSON.stringify(data),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (type == '0' && data.count != null) {
                        parent.find('.datesFilter').text('inicio:' + startD)
                        parent.find('.countDisplay').text('Pedidos:' + data.count + '| $' + ('' + data.total
                            .toLocaleString('en')));

                    } else {

                        if (data.count != null) {
                            parent.find('.datesFilter').text('inicio:' + startD)
                            parent.find('.countDisplay').text(data.count);
                        } else {
                            parent.find('.errorDisplay').text(data.message);
                        }
                    }
                })
        });
        $('#selectCommerce').change(() => {

            let id = +$('#selectCommerce').val()

            fetch('/administrator/categories/showByCommerce2?id=' + id, {
                    method: 'get',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    $('#categories').empty();
                    data.data.forEach(element => {

                        $('#categories').append(
                            `<option value="${element.id}">${element.name}</option>`)
                    });
                })
        })

    </script>
@endpush
