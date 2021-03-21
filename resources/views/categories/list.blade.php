@extends('layouts.app', ['page' => 'Categorías de productos'])

@section('content')
<div class="header bg-gradient-info pb-6 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('categories.index')}}">Categorías de productos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{route('categories.create')}}" class="btn btn-neutral">Crear categoría</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    <div class="card shadow">
        <div class="card-header border-0">
            <form action="{{route('categories.index')}}" method="get">
                <div class="row align-items-center">
                    <div class="col">
                        @if(Auth::user()->rol_id != 2)
                        <div class="form-group">
                            <label>Comercio</label>
                            <input type="text" placeholder="Nombre del comercio" value="{{request()->bussiness_name}}" name="bussiness_name" class="form-control">
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" placeholder="Nombre de la categoría" value="{{request()->name}}" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
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
                        <button class="btn btn-info btn-block btnFilterEraseCategory">Borrar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
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
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th></th> -->
                                    <th scope="col">Nombre</th>
                                    <th>Comercio</th>
                                    <th scope="col">Estado</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @isset($categories)
                                @foreach($categories as $item)
                                <tr id="{{$item->id}}">
                                    {{--
                                    <td>
                                    @if(Storage::disk('public')->exists($item->img_category))
                                    <img class="profile-user-img img-fluid mx-auto d-block " width="100" src="{{ asset('storage/'.$item->img_category) }}" alt="User profile picture">
                                    @else
                                    <img class="profile-user-img img-fluid mx-auto d-block " width="100" src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg" alt="User profile picture">
                                    @endif
                                    </td>
                                    --}}
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->getCommerce->bussiness_name}}</td>
                                    <td>{{$item->state==1?'Activo':'Inactivo' }}</td>
                                    <td>
                                        <a id="{{$item->id}}" href="{{route('categories.edit', [$item->id])}}" class="btn btn-sm btn-warning" style="color: white" href=""><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger btnDeleteCategories"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear Cateogría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if(Auth::user()->rol_id != 2)
                    <div class="form-group">
                        <label>Comercio *</label>
                        <select name="commerce_id" class="form-control" required>
                            <option value="" selected disabled>Seleccione un comercio</option>
                            @foreach($commerces as $commerce)
                            <option value="{{$commerce->id}}">{{$commerce->bussiness_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="commerce_id" value="{{Auth::user()->getCommerce->id}}">
                    @endif
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="name" id="input-name" class="form-control" placeholder="Nombre de la categoria" required>
                    </div>
                    <div class="form-group">
                        <label>Imagen *</label>
                        <input type="file" name="img_category" class="form-control imgView" id="img_category" required lang="en">
                    </div>
                    <div class="form-group">
                        <label>Descripción *</label>
                        <textarea name="description" class="form-control" required placeholder="Descripción" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-info btn-block"> <i class="fat-add"></i> Añadir Categoría</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualizar Cateogría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('categories.update',[0])}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" id="idCat" name="id" data-imgRoute="{{asset('storage/categories_img/')}}">
                    <img style="height:100px;width:100px" alt="" class="d-block m-auto imgView">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label" for="input-name">Nombre *</label>
                                <input type="text" name="name" id="input-name" class="form-control  nameC" placeholder="Nombre" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="state">Estado</label>
                                <select class="form-control" name="state" id="state">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Imagen</label>
                        <input type="file" name="img_category" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="parent">Parent</label>
                                <select multiple class="form-control" name="parent" id="parent">
                                    @foreach ($categories as $item)
                                    <option value="{{$item->id}}">{{ $item->parent == $item->id ? 'selected' : ''}} {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="order">Order</label>
                                <input type="text" class="form-control" name="order" id="order" aria-describedby="helpId" placeholder="Escriba su orden">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Descripción</label>
                                <textarea name="description" class="form-control desC" placeholder="Descripción" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush