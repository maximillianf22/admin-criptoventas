@extends('layouts.app', ['page' => 'Productos'])

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
                            @if(Auth::user()->rol_id != 2)
                            <li class="breadcrumb-item"><a href="{{route('products.commerce.index')}}">Elegir un
                                    comercio</a></li>
                            @endif
                            <li class="breadcrumb-item"><a
                                    href="{{route('products.commerce.show', [$commerce->id])}}">Productos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">

    <div class="row mt-5">
        <div class="col">
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                - {{$error}} <br>
                @endforeach
            </div>
            @endif
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Productos</h3>
                        </div>
                        <div class="col text-right">
                            @if($commerce->getCommerceType->name == 'Restaurante')
                            <a class="btn btn-sm btn-info"
                                href="{{route('commerce.restaurant.create', [$commerce->id])}}">Crear producto</a>
                            @elseif($commerce->getCommerceType->name == 'Supermercado')
                            <a class="btn btn-sm btn-info"
                                href="{{route('commerce.market.create', [$commerce->id])}}">Crear producto</a>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <div class="card-header border-0">
                            <form action="{{route('products.commerce.show',['commerce'=>$commerce->id])}}" method="get">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="form-group mb-1">
                                            <label>Producto</label>
                                            <input type="text" name="name" value="{{request()->name}}"
                                                class="form-control" placeholder="Producto">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label>Categoria</label>
                                            <select value="" name="category_name" class="form-control">
                                                <option value="" selected disabled>Seleccione una categoria</option>
                                                @foreach($categories as $cat)
                                                <option value="{{$cat->id}}"
                                                    {{request()->category_name == $cat->id ? 'selected': ''}}>
                                                    {{$cat->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class=" col">
                                        <div class="form-group mb-1">
                                            <label>Estado</label>
                                            <select name="state" class="form-control">
                                                <option value="-1"
                                                    {{ request()->state == -1 || is_null(request()->state) ? 'selected' : ''}}>
                                                    Todos</option>
                                                @foreach(Config::get('const.states') as $state => $value)
                                                <option value="{{$state}}"
                                                    {{request()->state === "".$state ? 'selected' : ''}}>
                                                    {{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-info btn-block ">Filtrar</button>
                                        <a class="btn btn-info btn-block "
                                            href="{{route('products.commerce.show',['commerce'=>$commerce->id])}}">Borrar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Estado</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($products->count())
                                @foreach($productsSort as $product)

                                <tr id="{{$product->id}}">
                                    <td>
                                        @if(Storage::disk('public')->exists($product->img_product))
                                        <img class="profile-user-img rounded-circle img-fluid mx-auto d-block "
                                            width="80" src="{{ asset('storage/'.$product->img_product) }}"
                                            alt="User profile picture">
                                        @else
                                        <img class="profile-user-img rounded-circle img-fluid mx-auto d-block "
                                            width="80"
                                            src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg"
                                            alt="User profile picture">
                                        @endif
                                    </td>
                                    <td>{{$product->name}}</td>
                                    <td>{{Str::limit($product->getCategory->name)}}</td>
                                    <td>{{Config::get('const.states')[$product->state]}}</td>
                                    <td>
                                        @if($product->getCategory->get_commerce->commerce_type_vp == 9)
                                        <button class="btn btn-sm btn-info btnEditPriceR" style="padding: 0.3rem .7rem;"><i class="fas fa-dollar-sign"
                                                aria-hidden="true"></i></button>
                                        <a class="btn btn-sm btn-warning"
                                            href="{{route('restaurant.edit', [$product->id])}}"><i
                                                class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger btnEraseRestaurant"><i
                                                class="fas fa-trash-alt"></i></button>
                                        @elseif($product->getCategory->get_commerce->commerce_type_vp == 10)
                                           <button class="btn btn-sm btn-info btnEditPrice" style="padding: 0.3rem .7rem;"><i class="fas fa-dollar-sign"
                                                aria-hidden="true"></i></button>
                                        <a class="btn btn-sm btn-warning"
                                            href="{{route('market.edit', [$product->id])}}"><i
                                                class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger btnEraseMarket"><i
                                                class="fas fa-trash-alt"></i></button>

                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center">
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
        <br /> <br />
        <!-- Editar supermarket -->
        <div class="modal fade" id="modalEditPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-sm " role="document">
                <form id="formPrices" class="precios" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="padding-bottom: 0;">
                            <h5 class="modal-title">Editar precios</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding-bottom: 0; padding-top: 0;">
                        <hr class="mt-3 mb-3">
                            <div id="pricecontainer" class="row text-center mb-1">
                                <div class="col"></div>
                            </div>
                            <input type="hidden" id="inputprice" class="inputprice"
                                        value="">
                            <input type="hidden" id="inputcommerce" class="inputcommerce"
                                        value="{{$commerce->id}}">
                            @if ($profiles->count() > 0)
                                @foreach ($profiles as $profile)

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
                                                    <input type="number" name="priceLista[{{ $profile->id }}][value]" min="1"
                                                        value=""
                                                        class="inputname form-control price_List{{$profile->id}}" placeholder="Precio">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="contenidoprice" class="modal-body">
                        </div>

                        <div class="modal-footer modal-footer-uniform" style="padding-top: 0;">
                            <button type="button" class="btn btn-bold btn-pure btn-secondary"
                                data-dismiss="modal">Cerrar</button>
                            <button type="submit"
                                class="btn btn-bold btn-pure btn-primary float-right btnSavePrices">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Editar Restaurant -->
        <div class="modal fade" id="modalEditPriceR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-sm " role="document">
                <form id="formPricesR" class="preciosR" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header" style="padding-bottom: 0;">
                            <h3 class="modal-title">Editar precios</h3>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding-bottom: 0; padding-top: 0;">
                            <hr class="mt-3 mb-3">
                            <div id="pricecontainerR" class="row text-center mb-1">
                                <div class="col">Precio</div>
                                <div id="contenidopriceR" class="modal-body">
                             </div>
                            </div>
                        </div>
                        <div class="modal-footer modal-footer-uniform" style="padding-top: 0;">
                            <button type="button" class="btn btn-bold btn-pure btn-secondary"
                                data-dismiss="modal">Cerrar</button>
                            <button type="submit"
                                class="btn btn-bold btn-pure btn-primary float-right btnSavePricesR">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endsection

        @push('js')
        <script>
        $(".btnEditPrice").on("click", function(e) {
            e.preventDefault();
            let id = $(this).parents("tr").attr("id");
            $('#inputprice').val(""+id)
            $('#inputcommerce').val()
            console.log(id)
            let object = document.getElementById('inputprice')
                    console.log(object)
            $.ajax({
                type: "get",
                url: "/administrator/product/updatePrices/" + id,
                dataType: "json",
                success: function(response) {
                    let data = response.data
                    $('.inputname').empty()
                    document.querySelectorAll(".inputname").forEach(function(el) {
                    el.value=""
                    });
                    data.map(function(p) {
                        console.log(p)
                        $(".price_List"+p.profile_vp).val(p.value)
                    })
                    $("#modalEditPrice").modal();
                },
            });
        });
        /* Restaurant */
        $(".btnEditPriceR").on("click", function(e) {
            e.preventDefault();
            let id = $(this).parents("tr").attr("id");
            $.ajax({
                type: "get",
                url: "/administrator/product/updatePricesR/" + id,
                dataType: "json",
                success: function(response) {
                    let object = document.getElementById('contenidopriceR')
                    let data = response.data
                    console.log(data)
                    $('#contenidopriceR').empty()
                    data.map(function(p) {
                        console.log(p)
                        $('#contenidopriceR').append(
                            `<div class="row">
                                   <div class="col">
                                       <div class="form-group">
                                           <div class="input-group">
                                               <div class="input-group-prepend">
                                                   <span class="input-group-text">$</span>
                                                </div>
                                                <input  type="number" name="publicPriceR[${p.product_id}]" min="1"
                                                    class="form-control products_id"  value="${p.value}" placeholder="Precio">
                                            </div>
                                        </div>
                                    </div>
                                </div>`
                        )
                    })
                    $("#modalEditPriceR").modal();
                },
            });
        });

        $('.btnSavePrices').on('click', function(e) {
            e.preventDefault()
            let dat = []
            let doc = document.querySelectorAll(".products_id")
            let form = new FormData(document.getElementById('formPrices'))
            form.append('idproduct',$('#inputprice').val())
            form.append('idcommerce',$('#inputcommerce').val())
            console.log(form)
          $.ajax({
                type: "post",
                url: "/administrator/product/fungetupdatePrices",
                data: form,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function() {

                },
                success: function(response) {
                    if (response.data == 'ok') {
                        $('#modalEditPrice').modal('hide')
                      location.reload();

                    }
                }
            });
        })

        $('.btnSavePricesR').on('click', function(e) {
            e.preventDefault()
            let dat = []
            let doc = document.querySelectorAll(".product_id")
            let form = new FormData(document.getElementById('formPricesR'))
            $.ajax({
                type: "post",
                url: "/administrator/product/fungetupdatePricesR",
                data: form,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function() {

                },
                success: function(response) {
                    if (response.data == 'ok') {
                        $('#modalEditPriceR').modal('hide')
                        location.reload();

                    }
                }
            });
        })
        </script>
        @endpush
