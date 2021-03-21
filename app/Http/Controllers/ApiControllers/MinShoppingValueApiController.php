<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Commerce\MinimumShoppingValueController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MinShoppingValueApiController extends MinimumShoppingValueController
{
    public function getMins(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'commerce_id' => 'required|required|exists:commerces,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $mins = $this->funShowByCommerce($request);
        return response()->json(['code' => 200, 'data' => $mins->getData()->data], 200);
    }
}
