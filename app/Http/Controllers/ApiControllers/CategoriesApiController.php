<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\CategoriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesApiController extends CategoriesController
{
    public function getCommerceProductsCategories(Request $request)
    {

        $Validation = Validator::make($request->all(), [
            'id' => 'exclude_if:id,0|required|exists:commerces,id'
        ], [
            'id.required' => 'Debe enviar el id que desea buscar',
            'id.exists' => 'No se encontraron registros con searchID'
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
        $dataRes = $response->original;
        if ($dataRes['code'] == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes['data']->where('state', 1)], 200);
        } else {
            return $response;
        }
    }
}
