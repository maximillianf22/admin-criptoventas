@extends('layouts.app', ['page' => 'Distribuidores'])

@section('content')
    <div class="header bg-gradient-info pb-5 pt-5">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('administrator.home') }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('distributors.index') }}">Distribuidores</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Listado</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <a href="{{ route('distributors.create') }}" class="btn btn-neutral">Crear un distribuidor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-3">
        <div class="row mt-5">
            <div class="col">
                @if (Session::has('success'))
                    <div class="alert alert-info">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card shadow">
                    <div class="card-header border-0">
                        <form action="{{ route('distributors.index') }}" method="get">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="form-group mb-1">
                                        <label>Documento</label>
                                        <input type="text" name="document" class="form-control" placeholder="Documento"
                                            value="{{ request()->document }}">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label>Nombre completo</label>
                                        <input type="text" name="fullname" class="form-control"
                                            placeholder="Nombre completo" value="{{ request()->fullname }}">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group mb-1">
                                        <label>Celular</label>
                                        <input type="text" name="cellphone" class="form-control" placeholder="Celular"
                                            value="{{ request()->cellphone }}">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label>Estado</label>
                                        <select name="state" class="form-control">
                                            <option value="-1"
                                                {{ request()->state == -1 || is_null(request()->state) ? 'selected' : '' }}>
                                                Todos</option>
                                            @foreach (Config::get('const.states') as $state => $value)
                                                <option value="{{ $state }}"
                                                    {{ request()->state === '' . $state ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <button class="btn btn-info btn-block ">Filtrar</button>
                                    <button class="btn btn-info btn-block btnFilterEraseDistributors">Borrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre completo</th>
                                    <th scope="col">Correo electrónico</th>
                                    <th scope="col">Celular</th>
                                    <!-- <th scope="col">Perfil</th> -->
                                    <th scope="col">Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($distributors as $distributor)
                                    <tr id="{{ $distributor->id }}">
                                        <td></td>
                                        <td>{{ $distributor->getUser->getDocType ? $distributor->getUser->getDocType->extra : '' }}
                                            {{ $distributor->getUser ? $distributor->getUser->document : 'No tiene' }}</td>
                                        <td>{{ $distributor->getUser->name }} {{ $distributor->getUser->last_name }}</td>
                                        <td>{{ $distributor->getUser->email ? $distributor->getUser->email : 'No tiene' }}
                                        </td>
                                        <td>{{ $distributor->getUser->cellphone }}</td>
                                        <!-- <td>{{ $distributor->getUser->getRol->name }}</td> -->
                                        <td>{{ Config::get('const.user_states')[$distributor->getUser->user_state] }}</td>
                                        <td>
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('distributors.edit', [$distributor->id]) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('distributors.show', [$distributor->id]) }}"><i
                                                    class="fas fa-eye"></i></a>
                                            <button class="btn btn-danger btn-sm btnDeleteDistributor"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
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
