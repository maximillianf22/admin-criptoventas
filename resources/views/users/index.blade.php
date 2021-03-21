@extends('layouts.app', ['page' => 'Usuarios'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Usuarios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{route('users.create')}}" class="btn btn-neutral">Crear usuario</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">

    <div class="row mt-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <form action="{{route('users.index')}}" method="get">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Documento</label>
                                    <input type="text" name="document" class="form-control" placeholder="Documento" value="{{request()->document}}">
                                </div>
                                <div class="form-group mb-1">
                                    <label>Celular</label>
                                    <input type="text" name="cellphone" class="form-control" placeholder="Celular" value="{{request()->cellphone}}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mb-1">
                                    <label>Rol</label>
                                    <select name="rol_id" class="form-control">
                                        <option value="-1">Todos</option>
                                        @foreach($roles as $rol)
                                        <option value="{{$rol->id}}" {{request()->rol_id == $rol->id ? 'selected': ''}}>{{$rol->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label>Estado</label>
                                    <select name="state" class="form-control">
                                        <option value="-1" {{ request()->state == -1 || is_null(request()->state) ? 'selected' : ''}}>Todos</option>
                                        @foreach(Config::get('const.states') as $state => $value)
                                        <option value="{{$state}}" {{request()->state === "".$state ? 'selected' : ''}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <button class="btn btn-info btn-block ">Filtrar</button>
                                <button class="btn btn-info btn-block btnFilterEraseUser">Borrar</button>
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
                                <th scope="col">Rol</th>
                                <th scope="col">Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($users->count())
                            @foreach($users as $user)
                            <tr id="{{$user->id}}">
                                <td>
                                    @if(Storage::disk('public')->exists($user->photo))
                                    <img class="profile-user-img rounded-circle mx-auto d-block " width="70" src="{{ asset('storage/'.$user->photo) }}" alt="User profile picture">
                                    @else
                                    <img class="profile-user-img rounded-circle mx-auto d-block " width="70" src="https://www.aalforum.eu/wp-content/uploads/2016/04/profile-placeholder.png" alt="User profile picture">
                                    @endif
                                </td>
                                <td>{{$user->getDocType->extra}}. {{$user->document}}</td>
                                <td>{{$user->name}} {{$user->last_name}}</td>
                                <td>{{$user->email ? $user->email : 'No tiene'}}</td>
                                <td>{{$user->cellphone}}</td>
                                <td>{{$user->getRol->name}}</td>
                                <td>{{Config::get('const.user_states')[$user->user_state]}}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{route('users.edit', [$user->id])}}"><i class="fas fa-edit"></i></a>
                                    <button class="btn btn-sm btn-danger btnDeleteUser"><i class="fas fa-trash-alt"></i></button>
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