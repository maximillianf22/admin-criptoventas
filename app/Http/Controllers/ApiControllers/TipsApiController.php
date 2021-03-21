<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TipsController;
use Illuminate\Support\Facades\Validator;

class TipsApiController extends TipsController
{
    public function getListTips(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'id' => 'required|exists:commerces,id'
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
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
}
