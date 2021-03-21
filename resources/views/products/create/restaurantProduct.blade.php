@extends('layouts.app', ['page' => 'Productos'])

@section('content')
    <div class="header bg-gradient-info pb-5 pt-5">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col text-right">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ route('administrator.home') }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('products.commerce.show', [$commerceId]) }}">Productos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    - {{ $error }} <br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('restaurant.store') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="commerceId" value="{{ $commerceId }}">
            <div class="row mt-5">
                <div class="col-4">
                    <div class="card">
                        <img class="card-img-top imgUpdate"
                            src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg"
                            alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">Datos del producto</h5>
                            <div class="form-group">
                                <label>Imagen *</label>
                                <input type="file" name="imgProduct" class="form-control inputImg">
                            </div>
                            <div class="form-group">
                                <label>Categoria *</label>
                                <div class="row">
                                    @if ($categories->count() > 0)
                                        @foreach ($categories as $category)
                                            <div class="col-6">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                        class="custom-control-input" id="cat_{{ $category->id }}">
                                                    <label class="custom-control-label"
                                                        for="cat_{{ $category->id }}">{{ $category->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col">
                                            <p class="mb-0">No hay categorias creadas</p>
                                            <a href="{{ route('categories.index') }}">Crear categorias</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Destacado </label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" value="1" name="outstanding" id="outstanding"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="outstanding">Agregar a producto destacado
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descripción *</label>
                                <textarea name="description" cols="30" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-white border-0">
                            <h3 class="mb-0">Características del producto</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col">
                                    <label>Nombre *</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nombre del producto">
                                </div>
                                <div class="form-group col">
                                    <label>Precio *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input name="value" type="number" class="form-control" placeholder="Precio"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label>Descuento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">-</span>
                                        </div>
                                        <input type="number" name="discount" class="form-control" min="1">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group col-3">
                                                                        <label>En oferta</label>
                                                                        <select name="" class="form-control">
                                                                            <option value="">No</option>
                                                                            <option value="">Si</option>
                                                                        </select>
                                                                    </div> -->
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-info btn-block mt-4"> Guardar </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')

@endpush
