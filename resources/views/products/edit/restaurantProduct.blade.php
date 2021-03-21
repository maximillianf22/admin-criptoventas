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
                                        href="{{ route('products.commerce.show', [$commerce_id]) }}">Productos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7">
        <div class="row mt-5 mb-3">
            <div class="col-4">
                <form action="{{ route('restaurant.update', [$restaurantProduct->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="commerceId" value="{{ $commerce_id }}">
                    <div class="card">
                        @if (Storage::disk('public')->exists($restaurantProduct->getProduct->img_product))
                            <img class="card-img-top imgUpdate"
                                src="{{ asset('storage/' . $restaurantProduct->getProduct->img_product) }}"
                                alt="User profile picture">
                        @else
                            <img class="card-img-top imgUpdate"
                                src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg"
                                alt="User profile picture">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">Datos del producto</h5>
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $restaurantProduct->getProduct->name }}" placeholder="Nombre del producto">
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label>Precio</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input name="value" type="number" class="form-control"
                                            value="{{ $restaurantProduct->value }}" placeholder="Precio" required>
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label>Descuento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">-</span>
                                        </div>
                                        <input type="number" name="discount" class="form-control" min="1"
                                            value="{{ $restaurantProduct->discount }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Imagen</label>
                                <input type="file" name="imgProduct" class="form-control inputImg">
                            </div>
                            <div class="form-group">
                                <label>Categorias</label>
                                @if ($categories->count() > 0)
                                    @foreach ($categories as $category)
                                        <div class="col">
                                            <div class="custom-control custom-checkbox">
                                                @foreach ($productCategories as $cat)
                                                    @if ($cat->category_id == $category->id)
                                                        <input type="checkbox" name="categories[]"
                                                            value="{{ $category->id }}" checked class="custom-control-input"
                                                            id="cat_{{ $category->id }}">
                                                    @else
                                                        <input type="checkbox" name="categories[]"
                                                            value="{{ $category->id }}" class="custom-control-input"
                                                            id="cat_{{ $category->id }}">
                                                    @endif
                                                @endforeach
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
                            <div class="form-group">
                                <label>Destacado </label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        {{ $restaurantProduct->getProduct->outstanding == 1 ? 'checked' : '' }}
                                        name="outstanding" id="outstanding" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="outstanding">Agregar a producto destacado
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col">
                                <label class="form-control-label" for="input-email">Estado *</label>
                                <select name="state" class="form-control">
                                    @foreach (Config::get('const.states') as $state => $value)
                                        <option value="{{ $state }}"
                                            {{ $restaurantProduct->getProduct->state == $state ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="description" cols="30" rows="5"
                                    class="form-control">{{ $restaurantProduct->getProduct->description }}</textarea>
                            </div>
                            <!-- <div class="form-group">
                                                <label>En oferta</label>
                                                <select name="" class="form-control">
                                                    <option value="">No</option>
                                                    <option value="">Si</option>
                                                </select>
                                            </div> -->
                        </div>
                    </div>
                    <button class="btn btn-info btn-block mt-4" type="submit"> Guardar </button>
                </form>
            </div>
            <div class="col-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            - {{ $error }} <br>
                        @endforeach
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-info">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card bg-secondary shadow">
                    <div class="card-header">
                        <h3>Ingredientes</h3>
                    </div>
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="mb-0">Adicionales</h4>
                            </div>
                            <div class="card-body p-0">
                                <form action="{{ route('ingredientsCategories.store') }}" method="post" autocomplete="off">
                                    <input type="hidden" name="restaurant_product_id" value="{{ $restaurantProduct->id }}">
                                    <input type="hidden" name="category_type_vp" value="6">
                                    <input type="hidden" name="max_ingredients" class="form-control" value="-1">
                                    <div class="row mb-3 mt-3 pl-4 pr-4">
                                        <div class="col-9">
                                            <input type="text" name="name" class="form-control" placeholder="Nombre"
                                                required>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-info btn-block" type="submit">Agregar</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <!-- Projects table -->
                                    <table class="table align-items-center table-flush text-center">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($ingredientCategories as $cat)
                                                @if ($cat->category_type_vp == 6)
                                                    <tr id="{{ $cat->id }}">
                                                        <td>{{ $cat->name }}</td>
                                                        <td>Ilimitado</td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm btnDetailCategory"
                                                                id="adicional"><i class="fas fa-eye"></i></button>
                                                            <button
                                                                class="btn btn-warning btn-sm btnEditIngredientCategory"><i
                                                                    class="fas fa-pencil-alt"></i></button>
                                                            <button
                                                                class="btn btn-danger btn-sm btnEraseIngredientCategory"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4 class="mb-0">Obligatorio</h4>
                            </div>
                            <div class="card-body p-0">
                                <form action="{{ route('ingredientsCategories.store') }}" method="post" autocomplete="off">
                                    <input type="hidden" name="restaurant_product_id" value="{{ $restaurantProduct->id }}">
                                    <input type="hidden" name="category_type_vp" value="7">
                                    <div class="row mb-3 mt-3 pl-4 pr-4">
                                        <div class="col-6">
                                            <input type="text" name="name" class="form-control" placeholder="Nombre"
                                                required>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="max_ingredients" class="form-control"
                                                placeholder="Cantidad" required>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-info btn-block" type="submit">Agregar</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <!-- Projects table -->
                                    <table class="table align-items-center table-flush text-center">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($ingredientCategories as $cat)
                                                @if ($cat->category_type_vp == 7)
                                                    <tr id="{{ $cat->id }}">
                                                        <td>{{ $cat->name }}</td>
                                                        <td>{{ $cat->max_ingredients }}</td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm btnDetailCategory"><i
                                                                    class="fas fa-eye"></i></button>
                                                            <button
                                                                class="btn btn-warning btn-sm btnEditIngredientCategory"><i
                                                                    class="fas fa-pencil-alt"></i></button>
                                                            <button
                                                                class="btn btn-danger btn-sm btnEraseIngredientCategory"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3 ">
                            <div class="card-header">
                                <h4 class="mb-0">Regular</h4>
                            </div>
                            <div class="card-body p-0">
                                <form action="{{ route('ingredientsCategories.store') }}" method="post" autocomplete="off">
                                    <input type="hidden" name="restaurant_product_id" value="{{ $restaurantProduct->id }}">
                                    <input type="hidden" name="category_type_vp" value="8">
                                    <div class="row mb-3 mt-3 pl-4 pr-4">
                                        <div class="col-6">
                                            <input type="text" name="name" class="form-control" placeholder="Nombre"
                                                required>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="max_ingredients" class="form-control"
                                                placeholder="Cantidad" required>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-info btn-block" type="submit">Agregar</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <!-- Projects table -->
                                    <table class="table align-items-center table-flush text-center">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($ingredientCategories as $cat)
                                                @if ($cat->category_type_vp == 8)
                                                    <tr id="{{ $cat->id }}">
                                                        <td>{{ $cat->name }}</td>
                                                        <td>{{ $cat->max_ingredients }}</td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm btnDetailCategory"><i
                                                                    class="fas fa-eye"></i></button>
                                                            <button
                                                                class="btn btn-warning btn-sm btnEditIngredientCategory"><i
                                                                    class="fas fa-pencil-alt"></i></button>
                                                            <button
                                                                class="btn btn-danger btn-sm btnEraseIngredientCategory"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDetailCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mb-0" id="exampleModalLabel">Gestionar ingredientes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" id="category_id">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" id="newIngredientName" placeholder="Nombre">
                                    </div>
                                    <div class="form-group">
                                        <label>Precio</label>
                                        <input type="number" disabled class="form-control" id="newIngredientValue"
                                            placeholder="Precio">
                                    </div>
                                    <button class="btn btn-info btn-block btnAddIngredient">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center table-flush text-center">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center bodyDetailCategories">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditIngredientCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm " role="document">
            <form id="formUpdateIngredientCatefory" method="POST">
                @csrf
                @method('put')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar categoria de ingrediente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" id="updateNameCategory" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Cantidad </label>
                            <input type="number" name="max_ingredients" id="updateMaxCategory" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalEditIngredient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm " role="document">
            <form id="formUpdateIngredient" method="POST">
                @csrf
                @method('put')
                <input type="hidden" id="updateIngredientId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar ingrediente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" id="updateIngredientName" placeholder="Nombre"
                                name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Precio </label>
                            <input type="number" name="value" id="updateIngredientPrice" placeholder="Precio"
                                class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')

@endpush
