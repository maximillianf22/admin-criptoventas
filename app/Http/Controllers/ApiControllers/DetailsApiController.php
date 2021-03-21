<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsApiController extends DetailsorderController
{

    public function getListDetails(Request $request){
        $Validation = Validator::make($request->all(),[
            'id' => 'required|exists:order_details,id'
               ]);

        if ($Validation->fails()){
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()], 400);
        }

        $response = $this->funGetListByOrder($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        }else{
            return $response;
        }
    }
}
