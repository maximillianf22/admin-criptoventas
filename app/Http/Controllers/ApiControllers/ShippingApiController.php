<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\ShippingController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingApiController extends ShippingController
{

    public function getShippingHour(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'commerce_id' => 'required|exists:commerces,id',
            'weekDay' => 'required'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }
        $request->merge(['api' => 1]);
        if ($request->weekDay == 0) {
            $request->merge(['weekDay' => 7]);
        }
        $response = $this->funShowByCommerceByWeekDay($request);

        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
}
