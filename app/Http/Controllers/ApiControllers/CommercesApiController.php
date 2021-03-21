<?php

namespace App\Http\Controllers\ApiControllers;

use App\Commerce;
use Illuminate\Http\Request;
use App\Http\Controllers\Administrator\CommercesController;
use App\PriceList;
use App\ProductCategory;
use App\RestaurantProduct;
use Illuminate\Support\Facades\Validator;


class CommercesApiController extends CommercesController
{

    public function postCommerce(Request $request)
    {
        $request->merge([
            'is_opened' => '0',
        ]);
        $response = $this->funCreate($request);

        return $response;
    }

    public function getCommercesByCategory(Request $request)
    {
        # code...
    }
    public function searchCommerce(Request $request)
    {
        $list = Commerce::where('bussiness_name', 'like', $request->keyWord . '%')
            ->where('state', 1)
            ->where('commerce_type_vp', $request->commerceType)
            ->with('getUser')->get() ?? [];
        return response()->json(['code' => 200, 'data' => $list], 200);
    }
    public function ofertsPorducts(Request $request)
    {
        $request->validate([
            'idCommerce' => 'exists:commerces,id',
            'profile_vp' => 'required'
        ]);
        $idCommerce = $request->idCommerce;
        $commerce = Commerce::where('id', $idCommerce)
            ->where('state', 1)
            ->first();
        if (empty($commerce))
            return  response()->json(['message' => 'this commerce has been removed', 'code' => 400], 400);

        $idProducts = ProductCategory::whereHas('getCategory', function ($category) use ($idCommerce) {
            $category->whereHas('getCommerce', function ($commerce) use ($idCommerce) {
                $commerce->where('id', $idCommerce)
                    ->where('state', 1);
            });
        })
            ->groupBy('product_id')
            ->get(['product_id'])
            ->pluck('product_id');
        $promociones = [];

        $profile = $request->profile_vp == 0 ? 3 : $request->profile_vp;
        if ($commerce->commerce_type_vp == 9)
            $promociones =  RestaurantProduct::where('discount', '<>', 'NULL')
                ->whereHas('getProduct', function ($product) {
                    $product->where('state', 1);
                })
                ->where('state', 1)
                ->whereIn('product_id', $idProducts)
                ->with('getProduct')
                ->get();

        if ($commerce->commerce_type_vp == 10) {
            $promociones =  PriceList::where('discount', '<>', 'NULL')
                ->whereHas('getProduct', function ($product) {
                    $product->where('state', 1);
                })
                ->where('state', 1)
                ->where('profile_vp', $profile)
                ->where('commerces_id', $commerce->id)
                ->with('getProduct.getMarketProduct.getUnit')
                ->get();
        }

        return response()->json(['data' => $promociones, 'code' => 200], 200);
    }
    public function outstandingProducts(Request $request)
    {
        $request->validate([
            'idCommerce' => 'exclude_if:idCommerce,0|exists:commerces,id',
            'profile_vp' => 'required'
        ]);
        $profile = $request->profile_vp == 0 ? 3 : $request->profile_vp;
        if ($request->idCommerce == 0) {
            $outstanding =  RestaurantProduct::whereNull('discount')
                ->whereHas('getProduct', function ($product) {
                    $product->where('state', 1)
                        ->where('outstanding', 1);
                })
                ->where('state', 1)
                ->with('getProduct')
                ->get();
            foreach ($outstanding as $item) {
                $item->commerces_id = $item->getProduct->getCategories()->first()->getCategory->getCommerce->id;
            }

            $outstanding2 = PriceList::whereNull('discount')
                ->whereHas('getProduct', function ($product) {
                    $product->where('state', 1)
                        ->where('outstanding', 1);
                })
                ->where('state', 1)
                ->where('profile_vp', $profile)
                ->with('getProduct.getMarketProduct.getUnit')
                ->get();
            $data = collect($outstanding2)->merge(collect($outstanding));

            return response()->json(['data' => $data, 'code' => 200], 200);
        }

        $idCommerce = $request->idCommerce;
        $commerce = Commerce::where('id', $idCommerce)
            ->where('state', 1)
            ->first();
        if (empty($commerce))
            return  response()->json(['message' => 'this commerce has been removed', 'code' => 400], 400);

        $idProducts = ProductCategory::whereHas('getCategory', function ($category) use ($idCommerce) {
            $category->whereHas('getCommerce', function ($commerce) use ($idCommerce) {
                $commerce->where('id', $idCommerce)
                    ->where('state', 1);
            });
        })
            ->groupBy('product_id')
            ->get(['product_id'])
            ->pluck('product_id');
        $outstanding = [];

        if ($commerce->commerce_type_vp == 9)
            $outstanding =  RestaurantProduct::whereNull('discount')
                ->whereHas('getProduct', function ($product) {
                    $product->where('state', 1)
                        ->where('outstanding', 1);
                })
                ->where('state', 1)
                ->whereIn('product_id', $idProducts)
                ->with('getProduct')
                ->get();

        if ($commerce->commerce_type_vp == 10) {
            $outstanding =  PriceList::whereNull('discount')
                ->whereHas('getProduct', function ($product) {
                    $product->where('state', 1)
                        ->where('outstanding', 1);
                })
                ->where('state', 1)
                ->where('profile_vp', $profile)
                ->where('commerces_id', $commerce->id)
                ->with('getProduct.getMarketProduct.getUnit')
                ->get();
        }

        return response()->json(['data' => $outstanding, 'code' => 200], 200);
    }

    public function getCommercesByType(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'tipo' => 'required'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400, 'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funGetListByType($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

    public function getCommerceDetails(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'id' => 'required|exists:commerces,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400, 'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funShow($request);
        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
}
