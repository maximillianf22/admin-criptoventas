<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Administrator\IngredientsCategoryController;
use Illuminate\Support\Facades\Validator;

class IngredientsApiControllers extends IngredientsCategoryController
{
    public function getProductIngredients(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'restaurant_product_id' => 'required|exists:ingredient_categories,restaurant_product_id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->ProductIngredient($request);

        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
}
