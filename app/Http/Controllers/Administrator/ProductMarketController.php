<?php

namespace App\Http\Controllers\Administrator;

use App\Category;
use App\Commerce;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\MarketProduct;
use App\Product;
use App\ProductCategory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Administrator\isNotNull;
use App\ParameterValue;
use App\PriceList;
use App\Rol;
use App\Unit;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

class ProductMarketController extends ProductController
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $units = Unit::where('state', 1)->get();
        $data = array(
            'units' => $units
        );
        return view('products.create.marketProduct', $data);
    }

    public function createMarketProduct($id)
    {
        $profiles = Rol::where('unique', '<>', 1)->get();
        $units = Unit::where('commerce_id', $id)->where('state', 1)->get();
        $categories = Category::where('commerce_id', $id)->where('state', 1)->get();
        $commerce = Commerce::where('id', $id)->first();
        $data = array(
            'commerce' => $commerce,
            'units' => $units,
            'profiles' => $profiles,
            'categories' => $categories
        );
        return view('products.create.marketProduct', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $store = $this->funCreate($request);
        if ($store->original['code'] == 200) {
            return redirect()->route('products.commerce.show', [$request->commerceId]);
        } else {
            return back();
        }
    }

    public function storeVariation(Request $request)
    {
        // dd($request->all());
        $store = $this->funCreateVariation($request);
        if ($store->original['code'] == 200) {
            return back()->with('success', 'Variacion creada correctamente');
        } else {
            return back();
        }
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

    public function showVariation($id)
    {
        request()->merge(['id' => $id]);
        $variation = $this->funShowVariation(request());
        return $variation;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profiles = Rol::where('unique', '<>', 1)->get();

        $product = Product::where('id', $id)->first();
        $category_id = $product->getCategories->first()->category_id;
        $commerce_id = Category::where('id', $category_id)->first()->commerce_id;

        $priceList = PriceList::where('products_id', $product->id)->get();

        $commerce = Commerce::where('id', $commerce_id)->first();
        $categories = Category::where('commerce_id', $commerce_id)->where('state', 1)->get();
        $units = Unit::where('commerce_id', $commerce_id)->where('state', 1)->get();

        $data = array(
            'product' => $product,
            'commerce' => $commerce,
            'units' => $units,
            'profiles' => $profiles,
            'categories' => $categories,
            'priceList' => $priceList
        );

        return view('products.edit.marketProduct', $data);
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
        $request->merge(['id' => $id]);
        $update = $this->funUpdate($request);
        if ($update->original['code'] == 200) {
            return back()->with('success', 'Producto actualizado correctamente');;
        } else {
            return back();
        }
    }

    public function updateVariation($id)
    {
        request()->merge(['id' => $id]);
        $update = $this->funUpdateVariation(request());
        if ($update->original['code'] == 200) {
            return back()->with('success', 'Variacion creada correctamente');;
        } else {
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        request()->merge(['id' => $id]);
        return $this->funDelete(request());
    }

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = MarketProduct::where('state', 1)->with('getProducts')->get();
        } else {
            $list = MarketProduct::where('category_id', $id)->with('getProducts')->get();
            // ->whereHas('getProducts',function($product){
            //     $product->where('state',1);
            // })->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'searchID' => 'required|exists:market_products,id'
        ], [
            'searchID.required' => 'Debe enviar el id que desea buscar',
            'searchID.exists' => 'No se encontraron registros con searchID'
        ]);
        $element = MarketProduct::where('id', $request->searchID)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funShowVariation(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:market_products,id'
        ]);
        $variation = MarketProduct::where('id', $request->id)->with('getProduct.getValues')->first();
        return response()->json(['code' => 200, 'data' => $variation], 200);
    }

    public function funCreate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'commerceId' => 'required',
            'imgProduct' => 'required|image|mimes:jpg,jpeg,png|max:200000',
            'description' => 'nullable',
            'unitId' => 'required|numeric',
            'categories' => 'required|array',
            'variationName' => 'max:499',
            'quantityContent' => 'required', //nose que mas validar
            'priceList' => 'required|array'
        ]);
        $result = DB::transaction(function () use ($request) {
            try {
                $product = new Product;
                $product->name = $request->name;
                $product->description = $request->description;
                $product->outstanding = $request->outstanding ?? null;
                if ($request->imgProduct) {
                    $product->img_product = $request->imgProduct->store('product_images', 'public');
                }
                $product->save();

                $marketProduct = new MarketProduct;
                $marketProduct->product_id = $product->id;
                $marketProduct->unit_id = $request->unitId;
                $marketProduct->variation_name = $request->variationName;
                $marketProduct->quantity_content = $request->quantityContent;
                $marketProduct->save();

                foreach ($request->categories as $id => $value) {
                    $product->getRealatedCategories()->attach($id);
                }

                $filterList = array_filter($request->priceList, function ($element) {
                    return $element['value'] != null;
                });
                // dd($filterList);
                $product->getPriceList()->sync($filterList);

                return response()->json(['code' => 200, 'data' => $marketProduct], 200);
            } catch (\Exception $e) {
                // dd($e);
                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear producto de supermercado'], 530);
            }
        });
        // dd($result);
        return $result;
    }

    public function funCreateVariation(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required',
            'product_id' => 'required|exists:products,id',
            'name' => 'required|max:499',
            'quantityContent' => 'required',
            'unitId' => 'required',
            'description' => 'nullable',
            'priceList' => 'required|array'
        ]);

        $result = DB::transaction(function () use ($request) {
            try {
                $parentProduct = Product::where('id', $request->product_id)->first();

                $variation = new Product();
                $variation->name = $request->name;
                $variation->img_product = $parentProduct->img_product;
                $variation->description = $request->description;
                $variation->save();

                $marketVariation = new MarketProduct();
                $marketVariation->product_id = $variation->id;
                $marketVariation->unit_id = $request->unitId;
                $marketVariation->parent = $request->product_id;
                $marketVariation->quantity_content = $request->quantityContent;
                $marketVariation->save();

                $filter = array_filter($request->priceList, function ($element) {
                    return $element['value'] != null;
                });

                $variation->getPriceList()->sync($filter);

                return response()->json(['code' => 200, 'data' => $marketVariation], 200);
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear producto de supermercado'], 530);
            }
        });
        return $result;
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|max:20|exists:market_products,id',
            'name' => 'required|max:499',
            'img_product' => 'image|mimes:jpg,jpeg,png|max:200000',
            'description' => 'nullable',
            'variation_name' => 'max:499',
            'priceList' => 'required|array',
            'categories' => 'required|array',
            'state' => 'required',
            'unitId' => 'required|exists:units,id', //nose que mas validar
            'quantityContent' => 'required' //nose que mas validar
        ]);

        $result = DB::transaction(function () use ($request) {
            try {
                $marketProduct = MarketProduct::where('id', $request->id)->first();
                $marketProduct->unit_id = $request->unitId;
                $marketProduct->quantity_content = $request->quantityContent;
                $marketProduct->state = $request->state;
                $marketProduct->update();

                $marketProduct->getProduct->name = $request->name;
                $marketProduct->getProduct->outstanding = $request->outstanding;
                $marketProduct->getProduct->description = $request->description;
                $marketProduct->getProduct->state = $request->state;

                if ($request->file('imgProduct')) {
                    Storage::disk("public")->delete($marketProduct->getProduct->img_product);
                    $marketProduct->getProduct->img_product = $request->imgProduct->store('product_images', 'public');
                }
                $marketProduct->getProduct->update();

                $marketProduct->getProduct->getRealatedCategories()->sync($request->categories);

                $filterList = array_filter($request->priceList, function ($element) {
                    return $element['value'] != null;
                });

                $marketProduct->getProduct->getPriceList()->sync($filterList);

                return response()->json(['code' => 200, 'data' => $marketProduct], 200);
            } catch (\Exception $e) {

                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar producto de supermercado'], 530);
            }
        });

        return $result;
    }

    public function funUpdateVariation(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:market_products,id',
            'name' => 'required|max:499',
            'quantityContent' => 'required',
            'unitId' => 'required',
            'description' => 'nullable',
            'priceList' => 'required|array',
            'state' => 'required'
        ]);

        $result = DB::transaction(function () use ($request) {
            try {
                $variation = MarketProduct::where('id', $request->id)->first();
                $variation->unit_id = $request->unitId;
                $variation->quantity_content = $request->quantityContent;
                $variation->state = $request->state;
                $variation->update();

                $variation->getProduct->name = $request->name;
                $variation->getProduct->description = $request->description;
                $variation->getProduct->state = $request->state;
                $variation->getProduct->update();

                $filter = array_filter($request->priceList, function ($element) {
                    return $element['value'] != null;
                });

                $variation->getProduct->getPriceList()->sync($filter);
                return response()->json(['code' => 200, 'data' => $variation], 200);
            } catch (\Throwable $th) {
                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar producto de supermercado'], 530);
            }
        });
        return $result;
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id'
        ]);

        $result = DB::transaction(function () use ($request) {
            try {
                $product = Product::where('id', $request->id)->first();
                $marketProduct = MarketProduct::where('product_id', $product->id)->first();

                $marketProduct->state = 2;
                $marketProduct->update();
                $product->state = 2;
                $product->update();

                return response()->json(['code' => 200, 'data' => $marketProduct], 200);
            } catch (\Exception $e) {

                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar producto de supermercado'], 530);
            }
        });

        return $result;
    }
}
