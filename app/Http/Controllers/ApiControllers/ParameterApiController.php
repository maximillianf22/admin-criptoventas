<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\ParameterValuesController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParameterApiController extends ParameterValuesController
{

    public function getParametersValues(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'parameter_id' => 'exclude_if:parameter_id,0|required|exists:parameters,id'
        ]);

        if ($Validation->fails()) {
            return response()->json(
                [
                    'code' => 400,
                    'data' => null,
                    'message' => $Validation->errors()->first()
                ],
                400
            );
        }

        $response = $this->funGetList($request);
        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
}
