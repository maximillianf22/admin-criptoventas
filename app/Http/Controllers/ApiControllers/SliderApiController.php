<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\slidersController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderApiController extends slidersController
{
    public function getList(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'commerce_id' => 'exclude_if:id,0|required|exists:sliders,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funGetList($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
    public function getListG()
    {
        $response = $this->funGetListG();
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

}
