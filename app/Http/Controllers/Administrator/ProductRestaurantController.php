<?php

namespace App\Http\Controllers\Administrator;

use App\Category;
use App\Http\Controllers\Controller;
use App\IngredientCategory;
use App\ParameterValue;
use App\Product;
use App\ProductCategory;
use App\RestaurantProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRestaurantController extends Controller
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

    public function createRestaurantProduct($id)
    {
        $categories = Category::where('commerce_id', $id)->where('state', 1)->get();
        $ingredientsType = ParameterValue::where('parameter_id', 3)->where('state', 1)->get();
        $data = array(
            'commerceId' => $id,
            'categories' => $categories,
            'ingredientType' => $ingredientsType
        );
        return view('products.create.restaurantProduct', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = $this->funCreate($request);
        if ($store->original['code'] == 200) {
            return redirect()->route('products.commerce.show', [$request->commerceId]);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        $productCategory = ProductCategory::where('product_id', $id)->get();
        $commerce_id = $productCategory->first()->getCategory->commerce_id;

        $categories = Category::where('commerce_id', $commerce_id)->get();
        $restaurantProduct = RestaurantProduct::where('product_id', $id)->first();

        $ingredientCategories = IngredientCategory::where('restaurant_product_id', $restaurantProduct->id)->where('state', 1)->get();
        $data = array(
            'commerce_id' => $commerce_id,
            'categories' => $categories,
            'ingredientCategories' => $ingredientCategories,
            'restaurantProduct' => $restaurantProduct,
            'productCategories' => $productCategory
        );
        return view('products.edit.restaurantProduct', $data);
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
            return back()->with('success', 'Producto actualizado');
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
    }

    public function funShow(Request $request)
    {
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|max:499',
            'commerceId' => 'required',
            'imgProduct' => 'required|image|mimes:jpg,jpeg,png|max:200000',
            'description' => 'required|max:499',
            'value' => 'required',
            'categories' => 'required|array'
        ]);
        // dd($request->all());
        $result = DB::transaction(function () use ($request) {
            try {
                $product = new Product();
                $product->name = $request->name;
                $product->description = $request->description;
                if ($request->imgProduct) {
                    $product->img_product = $request->imgProduct->store('product_images', 'public');
                }
                $product->outstanding = $request->outstanding ?? 0;
                $product->save();

                $restaurantProduct = new RestaurantProduct();
                $restaurantProduct->product_id = $product->id;
                $restaurantProduct->value = $request->value;
                $restaurantProduct->discount = $request->discount;
                $restaurantProduct->save();

                $product->getRealatedCategories()->sync($request->categories);

                return response()->json(['code' => 200, 'data' => $restaurantProduct], 200);
            } catch (\Exception $e) {
                // dd($e);
                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear producto de restaurante'], 530);
            }
        });
        return $result;
    }

    public function funUpdate(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:restaurant_products,id',
            'name' => 'required|max:499',
            'imgProduct' => 'image|mimes:jpg,jpeg,png|max:200000',
            'description' => 'required|max:499',
            'value' => 'required',
            'categories' => 'required|array'
        ]);

        $restaurantProduct = RestaurantProduct::where('id', $request->id)->first();
        $restaurantProduct->value = $request->value;
        $restaurantProduct->discount = $request->discount;
        $restaurantProduct->getProduct->state = $request->state;
        if ($request->imgProduct) {
            Storage::disk("public")->delete($restaurantProduct->getProduct->img_product);
            $restaurantProduct->getProduct->img_product = $request->imgProduct->store('product_images', 'public');
            $restaurantProduct->getProduct->update();
        }
        $restaurantProduct->state = $request->state;
        $restaurantProduct->update();
        $restaurantProduct->getProduct->outstanding = $request->outstanding ?? 0;
        $restaurantProduct->getProduct->description = $request->description;
        $restaurantProduct->getProduct->name = $request->name;
        $restaurantProduct->getProduct->update();

        $restaurantProduct->getProduct->getRealatedCategories()->sync($request->categories);
        return response()->json(['code' => 200, 'data' => $restaurantProduct], 200);
    }

    public function funDelete(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:restaurant_products,id'
        ]);

        try {
            $product = Product::where('id', $request->id)->first();
            $restaurantProduct = RestaurantProduct::where('product_id', $product->id)->first();
            DB::transaction(function () use ($product, $restaurantProduct) {
                $restaurantProduct->state = 2;
                $restaurantProduct->update();
                $product->state = 2;
                $product->update();
            });
            return response()->json(['code' => 200, 'data' => $restaurantProduct], 200);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar producto de restaurante'], 530);
        }
    }
}
