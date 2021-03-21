@extends('layouts.app', ['page' => 'Categorías de productos'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('categories.index')}}">Categorías de productos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    <form action="{{ route('categories.store') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="row mt-5">
            <div class="col-5">
                <div class="card card-profile shadow mb-3">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <img class="profile-user-img img-fluid mx-auto d-block" src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg" alt="User profile picture">
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4"></div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-body">
                        <label>Tipo de categoría</label>
                        <small>Subcategoría require una categoría padre</small>
                        <div class="form-group mb-0">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="cat1" id="cat1" name="radioCategoryType" checked class="custom-control-input radioCategoryTypes">
                                <label class="custom-control-label" for="cat1">Categoría principal</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="cat2" id="cat2" name="radioCategoryType" class="custom-control-input radioCategoryTypes">
                                <label class="custom-control-label" for="cat2">Subcategoría</label>
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->rol_id != 2)
                <div class="card mt-2 categoriesContainer" style="display: none;">
                    <div class="card-body">
                        <label class="mt-0 mb-0">Categoría padre (Opcional)</label><br>
                        <small>Las categorías listadas no tienen productos asignados</small>
                        <div class="row mt-2 categoriesList">

                        </div>
                    </div>
                </div>
                @else
                <div class="card mt-2 ">
                    <div class="card-body">
                        <label class="mt-0 mb-0">Categoría padre (Opcional)</label><br>
                        <small>Las categorías listadas no tienen productos asignados</small>
                        <div class="row mt-2">
                            @foreach($categories as $category)
                            <div class="col-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="cat_{{$category->id}}" disabled name="parent_id" value="{{$category->id}}" class="custom-control-input categoryRadios">
                                    <label class="custom-control-label" for="cat_{{$category->id}}">{{$category->name}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-7">
                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                    - {{$error}} <br>
                    @endforeach
                </div>
                @endif
                @if (Session::has('success'))
                <div class="alert alert-info">
                    Categoría creada existosamente
                </div>
                @endif
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">Crear categoría de producto</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Nombre *</label>
                                    <input type="text" name="name" id="input-name" class="form-control" value="{{old('name')}}" placeholder="Nombre de la categoría" required>
                                </div>
                            </div>
                            @if(Auth::user()->rol_id != 2)
                            <div class="col">
                                <div class="form-group">
                                    <label>Comercio *</label>
                                    <select name="commerce_id" class="form-control selectCommerce" required>
                                        <option value="" selected disabled>Seleccione un comercio</option>
                                        @foreach($commerces as $commerce)
                                        <option value="{{$commerce->id}}">{{$commerce->bussiness_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="commerce_id" value="{{Auth::user()->getCommerce->id}}">
                            @endif
                        </div>
                        <!-- <div class="form-group">
                            <label>Imagen *</label>
                            <input type="file" name="img_category" class="form-control inputImg" id="img_category" required lang="en">
                        </div> -->
                        <div class="form-group">
                            <label>Descripción *</label>
                            <textarea name="description" class="form-control" required placeholder="Descripción" id="exampleFormControlTextarea1" rows="3">{{old('description')}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info btn-block"> <i class="fat-add"></i> Añadir Categoría</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')

@endpush