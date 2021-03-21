@extends('layouts.app', ['page' => 'Categorías de comercios'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i
                                        class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('category.index')}}">Categorías de
                                    comercios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-right">
                    <button data-toggle="modal" data-target="#modalCreateCommerceCategory" class="btn btn-neutral">Crear
                        categoría</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        - {{$error}} <br>
        @endforeach
    </div>
    @endif
    @if (Session::has('success'))
    <div class="alert alert-info">
        Categoría creada exitosamente
    </div>
    @endif
    <div class="card shadow mt-4">
        <div class="card-header border-0">
            <form action="{{route('category.index')}}" method="get">
                <div class="row align-items-center">
                    <div class="col">
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control" placeholder="Nombre de la categoria"
                            value="{{request()->name}}">
                    </div>
                    <div class="col">
                        <label>Estado</label>
                        <select name="state" class="form-control">
                            <option value="-1"
                                {{request()->state === "-1" || is_null(request()->state) ? 'selected' : ''}}>Todos
                            </option>
                            @foreach(Config::get('const.states') as $state => $value)
                            <option value="{{$state}}" {{request()->state === "".$state ? 'selected' : ''}}>{{$value}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-info btn-block">Filtrar</button>
                        <a href="/administrator/commerces/category" class="btn btn-info btn-block ">Borrar</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <!-- <th></th> -->
                            <th scope="col">Nombre</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Descripción</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $item)
                        <tr id="{{$item->id}}">
                            {{--
                            <td>
                            @if(Storage::disk('public')->exists('categories_img/'.$item->photo))
                            <img class="profile-user-img img-fluid mx-auto d-block " width="100" src="{{ asset('storage/categories_img/'.$item->photo) }}"
                            alt="User profile picture">
                            @else
                            <img class="profile-user-img img-fluid mx-auto d-block " width="100"
                                src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg"
                                alt="User profile picture">
                            @endif
                            </td>
                            --}}
                            <td>{{$item->name}}</td>
                            <td>{{$item->state==1?'Activo':'Inactivo' }}</td>
                            <td>{{$item->description }}</td>
                            <td>
                                <a id="{{$item->id}}" href="{{route('category.edit',$item->id)}}"
                                    class="btn btn-sm btn-warning" style="color: white" href=""><i
                                        class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger btnDeleteCommerceCategories"><i
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
<div class="modal fade" id="modalCreateCommerceCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('category.store') }}" enctype="multipart/form-data" method="post" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear categoría</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input type="text" name="name" id="input-name" class="form-control"
                                    placeholder="Nombre de la categoría" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Tipo de comercio *</label>
                                <select class="form-control" name="commerce_type_vp" id="commerce_id" required>
                                    <option value="" selected disabled>Seleccionar tipo de comercio</option>
                                    @foreach ($type as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label>Imagen *</label>
                        <input type="file" name="photo" class="form-control imgView" required>
                    </div> -->
                    <div class="form-group">
                        <label>Descripción *</label>
        
                <textarea name="description" class="form-control" placeholder="Descripción"
                            id="exampleFormControlTextarea1" rows="3" required></textarea>
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

@endpush