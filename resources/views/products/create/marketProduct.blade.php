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
                                        href="{{ route('products.commerce.show', [$commerce->id]) }}">Productos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Crear</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-3">
        <form action="{{ route('market.store') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="commerceId" value="{{ $commerce->id }}">
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
                                <input type="file" name="imgProduct" class="form-control inputImg" required>
                            </div>
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input type="text" name="name" class="form-control" placeholder="Nombre del producto">
                            </div>
                            <div class="form-group">
                                <label>Nombre grupal (Opcional)</label>
                                <input type="text" name="variationName" class="form-control" placeholder="Nombre grupal">
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
                                <label>Categorias *</label>
                                <div class="row">
                                    @if ($categories->count() > 0)
                                        @foreach ($categories as $category)
                                            <div class="col">
                                                <div class="custom-control custom-checkbox">
                                                    <!-- <input type="hidden" name="" value="0"> -->
                                                    <input type="checkbox" name="categories[{{ $category->id }}]" value="1"
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
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                - {{ $error }} <br>
                            @endforeach
                        </div>
                    @endif
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-white border-0">
                            <h3 class="mb-0">Características del producto</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label>Contenido (Min. 1)</label>
                                    <input name="quantityContent" type="number" value="1" min="1" class="form-control"
                                        placeholder="Contenido del producto">
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Unidad *</label>
                                        <select name="unitId" class="form-control" required>
                                            <option value="" disabled selected>Seleccione una unidad</option>
                                            @if ($units->count() > 0)
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-3">
                                    <label>En oferta</label>
                                    <select name="" class="form-control">
                                        <option value="">No</option>
                                        <option value="">Si</option>
                                    </select>
                                </div> -->
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="description" cols="30" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-secondary shadow">
                        <div class="card-header">
                            <h3>Lista de precios</h3>
                        </div>
                        <div class="card-body pt-3 pb-3">
                            <div class="row text-center mb-1">
                                <div class="col"></div>
                                <div class="col"><small>Precio</small></div>
                                <div class="col"><small>Minimo de compra</small></div>
                                <div class="col"><small>Descuento</small></div>
                            </div>
                            @if ($profiles->count() > 0)
                                @foreach ($profiles as $profile)
                                    <input type="hidden" name="priceList[{{ $profile->id }}][commerces_id]"
                                        value="{{ $commerce->id }}">
                                    <div class="row">
                                        <div class="col">
                                            <div class="label-input m-0"> Precio Lista {{ $loop->iteration }} </div>
                                            <span class="text-muted">{{ $profile->name }}</span>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" name="priceList[{{ $profile->id }}][value]" min="1"
                                                        class="form-control" placeholder="Precio">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" name="priceList[{{ $profile->id }}][min]"
                                                        class="form-control" min="1" value="1">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Unidades</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">-</span>
                                                    </div>
                                                    <input type="number" name="priceList[{{ $profile->id }}][discount]"
                                                        class="form-control" min="1">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
