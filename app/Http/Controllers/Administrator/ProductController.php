<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Product;
use App\Category;
use App\Commerce;
use App\Rol;
use App\PriceList;
use App\ParameterValue;
use App\ProductCategory;
use Illuminate\Support\Facades\Auth;
use App\RestaurantProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function listCommerces()
    {
        $user = Auth::user();
        if ($user->rol_id == 2) {
            return redirect()->route('products.commerce.show', [$user->getCommerce->id]);
        } else {
            $commerces = Commerce::where('state', 1)->get();
            $commerceTypes = ParameterValue::where('parameter_id', 4)->get();

            if (!is_null(request()->bussiness_name)) {
                $commerces = $commerces->filter(function ($commerce) {
                    return false !== stripos($commerce->bussiness_name, request()->bussiness_name);
                });
            }
            if (!is_null(request()->nit)) {
                $commerces = $commerces->where('nit', request()->nit);
            }
            if (!is_null(request()->commerce_type) && request()->commerce_type != -1) {
                $commerces = $commerces->where('commerce_type_vp', request()->commerce_type);
            }
            if (!is_null(request()->state) && request()->state != -1) {
                $commerces = $commerces->where('state', request()->state);
            }


            $data = array(
                'commerces' => $commerces,
                'commerceTypes' => $commerceTypes
            );
            return view('products.commerces', $data);
        }
    }

    public function listProductsByCommerce(Request $request, $id)
    {
        $request->merge(['idCommerce' => $id]);
        $commerce = Commerce::where('id', $id)->first();
        $profiles = Rol::where('unique', '<>', 1)->with('get_Values')->get();
        $product = Product::where('id', $id)->first();
        $priceList = PriceList::where('products_id', $product->id)->get();
        $products = $this->funGetListByCommerce($request);

        $productsSort = [];
        $categoriesP = [];
        $commercesP = [];
        foreach ($products->original['data'] as $key => $product) {
            $productsSort[] = $product->getProduct;
            $categoriesP[] = $product->getCategory;
            $commercesP[] = $product->getCategory->getCommerce;
        }
             for ($i=0; $i < count($productsSort) ; $i++) {
            $productsSort[$i]->getCategory = $categoriesP[$i];
            $productsSort[$i] = json_decode($productsSort[$i]);
        }
        $productsSort = collect($productsSort)->sortBy('name');
        $productsSort= $productsSort->toArray();
        $categories= $this->listByCategoryCommerce($request);

        //dd($productsSort);

        if ($products->original['code'] == 200) {

            $data = array(
                'commerce' => $commerce,
                'profiles' => $profiles,
                'products' => $products->original['data'],
                'categories' => $categories->original['data'],
                'productsSort' => $productsSort,
                'priceList' => $priceList
            );

            return view('products.index', $data);
        } else {
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function listByCategoryCommerce(Request $request)
        {

            $category=Category::where('commerce_id',$request->idCommerce)->get();
            return response()->json(['code' => 200, 'data' => $category], 200);

        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = ProductCategory::where('state', 1)->with('getProducts')->get();
        } else {
            $list = ProductCategory::where('category_id', $id)->with('getProducts')->get();
            // ->whereHas('getProducts',function($product){
            //     $product->where('state',1);
            // })->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByCommerce(Request $request)
    {

        $request->validate([
            'idCommerce' => 'required|exists:commerces,id'
        ]);

        $list = ProductCategory::whereHas('getCategory', function ($q) use ($request) {
            $q->where('commerce_id', $request->idCommerce);
        })->whereHas('getProduct' , function ($q) use ($request){

            if ($request->has('name'))
                $q->where('name', 'like','%'. trim($request->name) . '%');


             if ($request->has('category_name'))
                $q->where('category_id',($request->category_name));

            if ($request->has('state') && $request->state != "-1") {
                $q->where('state', $request->state);

            } else {
                $q->whereIn('state', [0, 1]);
            }

        }
    )
            ->groupBy('product_id')
            ->get();

        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByCategoryByCommerce(Request $request)
    {
        $request->validate([
            'idCommerce' => 'required|exists:commerces,id',
            'idCategory' => 'exclude_if:idCategory,0|exists:categories,id'
        ]);

        if (empty($request->idCategory) || $request->idCategory == 0) {
            $list = Category::where('commerce_id', $request->idCommerce)
                ->with('getProductCategories.getProduct')
                ->get();
        } else {
            $list = Category::where('commerce_id', $request->idCommerce)
                ->where('id', $request->idCategory)
                ->with('getProductCategories.getProduct')
                ->get();
        }



        // $list = ProductCategory::whereHas('getCategory', function ($q) use ($request) {
        //     if (empty($request->idCategory) || $request->idCategory == 0) {
        //         $q->where('commerce_id', $request->idCommerce);
        //     } else {
        //         $q->where('commerce_id', $request->idCommerce)->where('category_id', $request->idCategory);
        //     }
        // })->with('getCategory')
        //     ->with(['getProduct' => function ($q) {
        //         $q->where('state', 1);
        //     }])
        //     ->groupBy('product_id')
        //     ->get();

        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByCategoryByCommerce2(Request $request)
    {
        $request->validate([
            'idCommerce' => 'required|exists:commerces,id',
            'idCategory' => 'exclude_if:idCategory,0|exists:categories,id'
        ]);

        if (empty($request->idCategory) || $request->idCategory == 0) {
            $list = Category::where('commerce_id', $request->idCommerce)
                ->with('getProductCategories.getProduct')
                ->get(["id"]);
        } else {
            $list = Category::where('commerce_id', $request->idCommerce)
                ->where('id', $request->idCategory)
                ->with('getProductCategories.getProduct')
                ->get(["id"]);
        }

        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'searchID' => 'required|exists:products,id'
        ], [
            'searchID.required' => 'Debe enviar el id que desea buscar',
            'searchID.exists' => 'No se encontraron registros con searchID'
        ]);
        $element = Product::where('id', $request->searchID)->with([
            'getMarketProduct.getProductVariations.getProduct',
            'getMarketProduct.getProductVariations.getUnit',
        ])->first();
        if (empty($element->getMarketProduct)) {
            $element->getRestaurantProduct = RestaurantProduct::where('product_id', $element->id)->first();
            $element->product_type = 2;
        } else {
            $element->getRestaurantProduct = null;
            $element->product_type = 1;
        }

        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'img_product' => 'required|image|mimes:jpg,jpeg,png|max:499',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $result = DB::transaction(function () use ($request) {
            try {
                $product = new Product;
                $productCategory = new ProductCategory;
                $product->name = $request->name;
                $product->img_product = $request->img_product;
                $product->description = $request->description;
                $product->save();

                $productCategory->category_id = $request->category_id;
                $productCategory->product_id = $product->id;
                $productCategory->save();

                return response()->json(['code' => 200, 'data' => $product], 200);
            } catch (\Exception $e) {

                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear producto'], 530);
            }
        });

        return $result;
    }

    public function fungetupdatePrices(Request $request)
    {
        $arrayPrices = $request->priceLista;
        foreach ($arrayPrices as $idProfile => $value) {
        $nuevoPrecio=$value["value"];
         if (empty($nuevoPrecio) || $nuevoPrecio==0) {
                $nuevoPrecio = 0;
            }
                $producto = PriceList::where('products_id', $request->idproduct)->where('profile_vp', $idProfile)->first();
                if(!empty($producto)){
                    $producto->value = $nuevoPrecio;
                    $producto->update();
                } else{
                    if(empty($producto)){
                        $nuevoprice= new PriceList();
                        $nuevoprice->products_id=$request->idproduct;
                        $nuevoprice->commerces_id=$request->idcommerce;
                        $nuevoprice->min=1;
                        $nuevoprice->profile_vp=$idProfile;
                        $nuevoprice->value=$nuevoPrecio;
                        $nuevoprice->save();
                    }
                }
        }
        return response()->json(['data' => 'ok']);
    }
    public function fungetupdatePricesR(Request $request)
    {
        $arrayPrices = $request->publicPriceR;
        foreach ($arrayPrices as $idProduct => $value) {
            $nuevoPrecio = str_replace(['$', ','], "", $value);
            if (empty($nuevoPrecio)) {
                $nuevoPrecio = 0;
            }else{
                $producto = RestaurantProduct::where('product_id', $idProduct)->first();
                $producto->value = $nuevoPrecio;
                $producto->save();
            }
        }

        return response()->json(['data' => 'ok']);
    }

    public function updatePrices($id)
    {
        $producto = PriceList::where('products_id', $id)->with('getProfile')->get();
        return response()->json(['data' => $producto]);
    }

    public function updatePricesRestaurant($id)
    {
        $producto = RestaurantProduct::where('product_id', $id)->get();
        return response()->json(['data' => $producto]);
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'required',
            'img_product' => 'required|image|mimes:jpg,jpeg,png|max:499',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);


        $result = DB::transaction(function () use ($request) {
            try {
                $product = Product::where('id', $request->id)->first();
                $productCategory = ProductCategory::where('id', $request->id);
                $product->name = $request->name;
                $product->img_product = $request->img_product;
                $product->description = $request->description;
                $product->save();
                $productCategory->category_id = $request->category_id;
                $productCategory->product_id = $product->id;
                $productCategory->save();

                return response()->json(['code' => 200, 'data' => $product], 200);
            } catch (\Exception $e) {

                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear producto'], 530);
            }
        });

        return $result;
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id'
        ]);

        $model = Product::where('id', $request->id)->first();
        $model->state = 2;

        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
    public function BuscadorProductos(Request $request)
    {

        $query =  Commerce::whereHas('getProducts.getProduct', function ($q) use ($request) {
            $q->Where('products.state', 1);
            $q->Where('products.name', 'like', '%'.$request->keyWord . '%');
            $q->orWhere('products.description', 'like', '%'.$request->keyWord . '%');
            $q->where('state',1);
        })->with(['getProducts.getProduct','getUser'])
        ->where('state',1);
        if ($request->has('commerceType'))
            if($request->commerceType!=0)
                $query ->where('commerce_type_vp',$request->commerceType);
        if($request->has('idCommerce'))
            if($request->idCommerce!=0)
                $query ->where('id',$request->idCommerce);

        return response()->json(['data' => $query->get(), 'code' => 200], 200);
    }

   /*  public function BuscadorByCommercio($keyWord, $commerce)
    {
        request()->merge(['idCommerce' => $commerce]);
        request()->validate(['idCommerce' => 'exists:commerces,id']);

        $query =  Category::with([
            'getReletedProducts' => function ($q2) use ($keyWord) {
                $q2->Where('products.state', 1);
                $q2->Where('products.name', 'like', $keyWord . '%');
                $q2->orWhere('products.description', 'like', $keyWord . '%');

            }
        ])
            ->whereHas('getReletedProducts', function ($q) use ($keyWord) {
                $q->Where('products.state', 1);
                $q->Where('products.name', 'like', $keyWord . '%');
                $q->orWhere('products.description', 'like', $keyWord . '%');
            })
            ->whereHas('getCommerce', function ($c) use ($keyWord) {
                $c->Where('state', 1);
            })
            ->where('state', 1)
            ->where('commerce_id', $commerce)
            ->get();

        $data = ['categories' => $query];
        return response()->json(['data' => $data, 'code' => 200], 200);
    } */
}
