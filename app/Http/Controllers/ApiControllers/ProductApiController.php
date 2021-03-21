<?php

namespace App\Http\Controllers\ApiControllers;

use App\Customer;
use App\Http\Controllers\Administrator\ProductController;
use App\Http\Controllers\Administrator\ProductMarketController;
use App\MarketProduct;
use App\PriceList;
use App\Product;
use App\ProductCategory;
use App\RestaurantProduct;  
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class ProductApiController extends ProductController
{
    //obligatorio
    public function getProductDetails(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'searchID' => 'required|exists:products,id',
            'idCustomer' => 'exclude_if:idCustomer,0|required|exists:customers,id'

        ], [
            'searchID.required' => 'Debe enviar el id que desea buscar',
            'searchID.exists' => 'No se encontraron registros con searchID'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funShow($request);

        $dataRes = $response->getData();
        if ($dataRes->code == 200) {

            $idRol = 3; //Rol de cliente
            $res = $dataRes->data;
            if ($request->idCustomer != 0) {
                $customers = Customer::where('id', $request->idCustomer)->with('getUser')->first();
                $idRol = $customers->getUser->rol_id;
            }

            if (!is_null($res->get_market_product) && !is_null($res->get_market_product->parent)) {
                $productCategory = ProductCategory::where('product_id', $res->get_market_product->parent)->with('getCategory.getCommerce')->first();
            } else {
                $productCategory = ProductCategory::where('product_id', $res->id)->with('getCategory.getCommerce')->first();
            }
            $commerce_type = $productCategory->getCategory->getCommerce->commerce_type_vp;
            if (!empty($commerce_type)) {
                if ($commerce_type == 9) {
                    $productRestaurant = RestaurantProduct::where('product_id', $res->id)->first();
                    $productDiscount = null;
                    if (!is_null($productRestaurant->discount)) {
                        $productDiscount = $productRestaurant->value - ($productRestaurant->value * $productRestaurant->discount / 100);
                    }
                    $res->value = [
                        'value' => $productRestaurant->value,
                        'min' => '',
                        'discount' => $productDiscount
                    ];
                } elseif ($commerce_type == 10) {
                    foreach ($res->get_market_product->get_product_variations as $variation) {
                        $priceListVariation = PriceList::where('products_id', $variation->get_product->id)->where('profile_vp', $idRol)->first(['value', 'min', 'discount']);
                        if (!is_null($priceListVariation) && !is_null($priceListVariation->discount)) {
                            $priceListVariation->discount = $priceListVariation->value - ($priceListVariation->value * $priceListVariation->discount / 100);
                        }
                        $variation->value = $priceListVariation;
                    }

                    $priceList = PriceList::where('products_id', $res->id)->where('profile_vp', $idRol)->first(['value', 'min', 'discount']);
                    if (!is_null($priceList) && !is_null($priceList->discount)) {
                        $priceList->discount = $priceList->value - ($priceList->value * $priceList->discount / 100);
                    }
                    $res->value = $priceList;
                    $res->getUnit = Unit::where('id', $res->get_market_product->unit_id)->first();
                }
            }

            return response()->json(['code' => 200, 'data' => $res], 200);
        } else {
            return $response;
        }
    }

    public function getProductsListCommerce(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'idCommerce' => 'required|exists:commerces,id',
            'idCustomer' => 'exclude_if:idCustomer,0|required|exists:customers,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funGetListByCommerce($request);
        $dataRes = $response->getData();
        if ($dataRes->code == 200) {

            $idRol = 3; //Rol de cliente
            $res = $dataRes->data;
            if ($request->idCustomer != 0) {
                $customers = Customer::where('id', $request->idCustomer)->with('getUser')->first();
                $idRol = $customers->getUser->rol_id;
            }

            foreach ($res as $keys => $product) {
                $productCategory = ProductCategory::where('id', $product->id)->with('getCategory.getCommerce')->first();
                $commerce_type = $productCategory->getCategory->getCommerce->commerce_type_vp;

                if (!empty($commerce_type)) {
                    if ($commerce_type == 9) {
                        $restaurantProduct = RestaurantProduct::where('product_id', $product->product_id)->first();
                        $productDiscount = null;
                        if (!is_null($restaurantProduct->discount)) {
                            $productDiscount = $restaurantProduct->value - ($restaurantProduct->value * $restaurantProduct->discount / 100);
                        }
                        $product->value = [
                            'value' => $restaurantProduct->value,
                            'min' => '',
                            'discount' => $productDiscount
                        ];
                    } elseif ($commerce_type == 10) {
                        $priceList = PriceList::where('products_id', $product->product_id)->where('profile_vp', $idRol)->first(['value', 'min', 'discount']);
                        if (!is_null($priceList) && !is_null($priceList->discount)) {
                            $priceList->discount = $priceList->value - ($priceList->value * $priceList->discount / 100);
                        }
                        $product->value = $priceList;
                        $productSuperMarket = MarketProduct::where('product_id', $product->product_id)->first();
                        $product->getUnit = Unit::where('id', $productSuperMarket->unit_id)->first();
                    }
                }
            }

            return response()->json(['code' => 200, 'data' => $res], 200);
        } else {
            return $response;
        }
    }

    public function getProductsListByCategoryByCommerce(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'idCommerce' => 'required|exists:commerces,id',
            'idCustomer' => 'exclude_if:idCustomer,0|required|exists:customers,id',
            'idCategory' => 'exclude_if:idCategory,0|exists:categories,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funGetListByCategoryByCommerce($request);
        $dataRes = $response->getData();
        //return response()->json(['code' => 200, 'data' => $dataRes], 200);

        if ($dataRes->code == 200) {

            $idRol = 3; //Rol de cliente
            $res = $dataRes->data;

            if ($request->idCustomer != 0) {
                $customers = Customer::where('id', $request->idCustomer)->with('getUser')->first();
                $idRol = $customers->getUser->rol_id;
            }

            foreach ($res as $key => $category) {
                foreach ($category->get_product_categories as $keyCat => $productCat) {
                    if ($productCat->get_product->state != 1) {
                        $index = array_search($productCat, $category->get_product_categories);
                        array_splice($category->get_product_categories, $index, 1);
                    } else {
                        $productCategory = ProductCategory::where('id', $productCat->id)->with('getCategory.getCommerce')->first();
                        $commerce_type = $productCategory->getCategory->getCommerce->commerce_type_vp;

                        if (!empty($commerce_type)) {
                            if ($commerce_type == 9) {
                                $restProduct = RestaurantProduct::where('product_id', $productCat->get_product->id)->first();
                                $productDiscount = null;
                                if (!is_null($restProduct->discount)) {
                                    $productDiscount = $restProduct->value - ($restProduct->value * $restProduct->discount / 100);
                                }
                                $productCat->value = [
                                    'value' => $restProduct->value,
                                    'min' => '',
                                    'discount' => $productDiscount
                                ];
                            } elseif ($commerce_type == 10) {
                                //return response()->json(['code' => 200, 'data' => $productCat], 200);
                                $priceList = PriceList::where('products_id', $productCat->get_product->id)->where('profile_vp', $idRol)->first(['value', 'min', 'discount']);
                                if (!is_null($priceList) && !is_null($priceList->discount)) {
                                    $priceList->discount = $priceList->value - ($priceList->value * $priceList->discount / 100);
                                }
                                $productCat->value = $priceList;
                                $productSuperMarket = MarketProduct::where('product_id', $productCat->get_product->id)->first();
                                $productCat->getUnit = Unit::where('id', $productSuperMarket->unit_id)->first();
                            }
                        }
                    }
                }
            }
            return response()->json(['code' => 200, 'data' => $res], 200);
        } else {
            return $response;
        }
    }



    public function getProductsByseacher(Request $request)
    {

        $Validation = Validator::make($request->all(), [

            'idCustomer' => 'exclude_if:idCustomer,0|required|exists:customers,id',
            'commerce_type' => 'exists:parameter_values,id',
            'idCommerce'=>'exists:commerces,id',
            'keyWord' => 'required|String'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->BuscadorProductos($request);
        $dataRes = $response->getData();
        //return response()->json(['code' => 200, 'data' => $dataRes], 200);

        if ($dataRes->code == 200) {

            $idRol = 3; //Rol de cliente
            $res = $dataRes->data;

            if ($request->idCustomer != 0) {
                $customers = Customer::where('id', $request->idCustomer)->with('getUser')->first();
                $idRol = $customers->getUser->rol_id;
            }

            foreach ($res as $key => $commerce) {
                foreach ($commerce->get_products as $keyCat => $productCat) {
                    if ($productCat->get_product->state != 1) {
                        $index = array_search($productCat, $commerce->get_products);
                        array_splice($commerce->get_products, $index, 1);
                    } else {
                        if( !preg_match_all('/'.$request->keyWord.'/i',$productCat->get_product->name)
                         && !preg_match_all('/'.$request->keyWord.'/i',$productCat->get_product->description)
                         ){
                            $index = array_search($productCat, $commerce->get_products);
                            array_splice($commerce->get_products, $index, 1);
                        }

                            if ($commerce->commerce_type_vp == 9) {
                                $restProduct = RestaurantProduct::where('product_id', $productCat->get_product->id)->first();
                                $productDiscount = null;
                                if (!is_null($restProduct->discount)) {
                                    $productDiscount = $restProduct->value - ($restProduct->value * $restProduct->discount / 100);
                                }
                                $productCat->value = [
                                    'value' => $restProduct->value,
                                    'min' => '',
                                    'discount' => $productDiscount
                                ];
                            } elseif ($commerce->commerce_type_vp == 10) {
                                //return response()->json(['code' => 200, 'data' => $productCat], 200);
                                $priceList = PriceList::where('products_id', $productCat->get_product->id)->where('profile_vp', $idRol)->first(['value', 'min', 'discount']);
                                if (!is_null($priceList) && !is_null($priceList->discount)) {
                                    $priceList->discount = $priceList->value - ($priceList->value * $priceList->discount / 100);
                                }
                                $productCat->value = $priceList;
                                $productSuperMarket = MarketProduct::where('product_id', $productCat->get_product->id)->first();
                                $productCat->getUnit = Unit::where('id', $productSuperMarket->unit_id)->first();
                            }

                    }
                }
            }
            return response()->json(['code' => 200, 'data' => $res], 200);
        } else {
            return $response;
        }
    }

    //New function apis
    public function getProductCommerce(Request $request) {

        $Validation = Validator::make($request->all(), [
            'idCommerce' => 'required|exists:commerces,id',
            'idCustomer' => 'exclude_if:idCustomer,0|required|exists:customers,id',
            'idCategory' => 'exclude_if:idCategory,0|exists:categories,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        
        $response = $this->funGetListByCategoryByCommerce2($request);
        $dataRes = $response->getData();

        $products = [];

        if ($dataRes->code == 200) {

            $idRol = 3; //Rol de cliente

            foreach ($dataRes->data as $item) {
                foreach ($item->get_product_categories as $product) {

                    if ($product->get_product->state == 1) {

                        $productCategory = ProductCategory::where('id', $product->id)->with('getCategory.getCommerce')->first();
                        $commerce_type = $productCategory->getCategory->getCommerce->commerce_type_vp;

                        if (!empty($commerce_type)) {

                            if ($commerce_type == 9) {
                                $restProduct = RestaurantProduct::where('product_id', $product->get_product->id)->first();
                                $productDiscount = null;
                                if (!is_null($restProduct->discount)) {
                                    $productDiscount = $restProduct->value - ($restProduct->value * $restProduct->discount / 100);
                                }
                                $product->value = [
                                    'value' => $restProduct->value,
                                    'min' => '',
                                    'discount' => $productDiscount
                                ];
                            } elseif ($commerce_type == 10) {
                                $priceList = PriceList::where('products_id', $product->get_product->id)->where('profile_vp', $idRol)->first(['value', 'min', 'discount']);

                                if (!is_null($priceList) && !is_null($priceList->discount)) {

                                    $priceList->discount = $priceList->value - ($priceList->value * $priceList->discount / 100);

                                }

                                $product->value = $priceList;
                                $productSuperMarket = MarketProduct::where('product_id', $product->get_product->id)->first();
                                $product->getUnit = Unit::where('id', $productSuperMarket->unit_id)->first();

                            }

                        }

                        array_push($products, $product);

                    }

                    
                }
            }

            return response()->json(['code' => 200, 'data' => $products], 200);

        } else {
            return $response;
        }
    }
}
