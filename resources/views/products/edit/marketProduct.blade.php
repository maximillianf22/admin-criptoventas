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
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-3">
        <form action="{{ route('market.update', [$product->getMarketProduct->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <input type="hidden" name="commerceId" value="{{ $commerce->id }}">
            <div class="row mt-5">
                <div class="col-4">
                    <div class="card">
                        @if (Storage::disk('public')->exists($product->img_product))
                            <img class="card-img-top imgUpdate" src="{{ asset('storage/' . $product->img_product) }}"
                                alt="User profile picture">
                        @else
                            <img class="card-img-top imgUpdate"
                                src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg"
                                alt="User profile picture">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">Datos del producto</h5>
                            <div class="form-group">
                                <label>Imagen (Opcional)</label>
                                <input type="file" name="imgProduct" class="form-control inputImg">
                            </div>
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="form-control"
                                    placeholder="Nombre del producto">
                            </div>
                            <div class="form-group">
                                <label>Nombre grupal (Opcional)</label>
                                <input type="text" name="variationName"
                                    value="{{ $product->getMarketProduct->variation_name }}" class="form-control"
                                    placeholder="Nombre grupal">
                            </div>
                            <div class="form-group">
                                <label>Destacado </label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" {{ $product->outstanding == 1 ? 'checked' : '' }}
                                        name="outstanding" id="outstanding" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="outstanding">Agregar a producto destacado
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-email">Estado *</label>
                                <select name="state" class="form-control">
                                    @foreach (Config::get('const.states') as $state => $value)
                                        <option value="{{ $state }}"
                                            {{ $product->getMarketProduct->state == $state ? 'selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Categorias *</label>
                                <div class="row">
                                    @if ($categories->count() > 0)
                                        @foreach ($categories as $category)
                                            <div class="col-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                        {{ $product->getCategories->contains('category_id', $category->id) ? 'checked' : '' }}
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
                    <button class="btn btn-info btn-block mt-3"> Guardar </button>
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
                    <div class="card bg-secondary shadow mb-3">
                        <div class="card-header bg-white border-0">
                            <h3 class="mb-0">Características del producto</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <label>Contenido (Min. 1)</label>
                                    <input name="quantityContent" type="number" value="1" min="1"
                                        value="{{ $product->getMarketProduct->quantity_content }}" class="form-control"
                                        placeholder="Contenido del producto">
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Unidad *</label>
                                        <select name="unitId" class="form-control" required>
                                            <option value="" disabled>Seleccione una unidad</option>
                                            @if ($units->count() > 0)
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $product->getMarketProduct->unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}</option>
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
                                <label>Descripción *</label>
                                <textarea name="description" cols="30"
                                    class="form-control">{{ $product->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-secondary shadow mb-3">
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
                                                        value="{{ $priceList->contains('profile_vp', $profile->id) ? $priceList->where('profile_vp', $profile->id)->first()->value : '' }}"
                                                        class="form-control" placeholder="Precio">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" name="priceList[{{ $profile->id }}][min]"
                                                        class="form-control" min="1"
                                                        value="{{ $priceList->contains('profile_vp', $profile->id) ? $priceList->where('profile_vp', $profile->id)->first()->min : 1 }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Und</span>
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
                                                        class="form-control" min="1"
                                                        value="{{ $priceList->contains('profile_vp', $profile->id) ? $priceList->where('profile_vp', $profile->id)->first()->discount : 1 }}">
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
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <div class="row">
                                <div class="col">
                                    <h3 class="mb-0">Variaciones del producto</h3>
                                </div>
                                <div class="col text-right">
                                    <button class="btn btn-info btn-sm btnAddVariation">Agregar variación</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush text-center">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Contenido</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if ($product->getMarketProduct->getProductVariations->count() > 0)
                                        @foreach ($product->getMarketProduct->getProductVariations as $variation)
                                            <tr id="{{ $variation->id }}">
                                                <td>{{ $variation->getProduct->name }}</td>
                                                <td>{{ $variation->quantity_content }} {{ $variation->getUnit->name }}</td>
                                                <td>{{ Config::get('const.states')[$variation->state] }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning btnEditVariation"><i
                                                            class="fas fa-edit"></i></button>
                                                    <button class="btn btn-sm btn-danger btnEraseMarket"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                No hay productos registrados
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="modalCreateVariation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form action="{{ route('market.variation.store') }}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Crear variacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <input type="hidden" name="commerce_id" value="{{ $commerce->id }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="text" name="name" class="form-control" placeholder="Nombre del producto">
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label>Contenido (Min. 1)</label>
                                <input name="quantityContent" type="number" value="1" min="1" class="form-control"
                                    placeholder="Contenido del producto">
                            </div>
                            <div class="form-group col">
                                <label>Unidad *</label>
                                <select name="unitId" class="form-control" required>
                                    <option value="" disabled>Seleccione una unidad</option>
                                    @if ($units->count() > 0)
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="description" cols="30" class="form-control"></textarea>
                        </div>
                        <hr class="mt-3 mb-3">
                        <label>Lista de precios</label>
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
                                                    <span class="input-group-text">Und</span>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalEditVariation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form id="formEditVariation" method="post">
                @csrf
                @method('put')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0" id="exampleModalLabel">Editar variacion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0">
                        <input type="hidden" name="commerce_id" value="{{ $commerce->id }}">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="row">
                            <div class="form-group col">
                                <label>Nombre *</label>
                                <input type="text" name="name" id="editVariationName" class="form-control"
                                    placeholder="Nombre del producto">
                            </div>
                            <div class="form-group col">
                                <label class="form-control-label" for="input-email">Estado *</label>
                                <select name="state" class="form-control">
                                    @foreach (Config::get('const.states') as $state => $value)
                                        <option value="{{ $state }}"
                                            {{ $product->getMarketProduct->state == $state ? 'selected' : '' }}>{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label>Contenido (Min. 1)</label>
                                <input name="quantityContent" id="editVariationContent" type="number" value="1" min="1"
                                    class="form-control" placeholder="Contenido del producto">
                            </div>
                            <div class="form-group col">
                                <label>Unidad *</label>
                                <select name="unitId" id="editVariationUnit" class="form-control" required>
                                    <option value="" disabled>Seleccione una unidad</option>
                                    @if ($units->count() > 0)
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descripción *</label>
                            <textarea name="description" id="editVariationDesc" cols="30" class="form-control"></textarea>
                        </div>
                        <hr class="mt-3 mb-3">
                        <label>Lista de precios</label>
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
                                                <input type="number" name="priceList[{{ $profile->id }}][value]"
                                                    id="editVariationPrice_{{ $profile->id }}" min="1" class="form-control"
                                                    placeholder="Precio">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="number" name="priceList[{{ $profile->id }}][min]"
                                                    id="editVariationMin_{{ $profile->id }}" class="form-control" min="1"
                                                    value="1">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Und</span>
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
                                                    id="editVariationDiscount_{{ $profile->id }}" class="form-control"
                                                    min="1">
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

