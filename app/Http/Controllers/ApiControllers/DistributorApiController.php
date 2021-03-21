<?php

namespace App\Http\Controllers\ApiControllers;
use App\Distributor;
use App\DistributorComissions;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Administrator\DistributorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistributorApiController extends DistributorController
{
    public function postDistributor(Request $request)
    {
        // return $request->all();
        $Validation = Validator::make($request->all(), [
            // 'document' => 'required|string|min:6|max:500',
            // 'document_type_vp' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10',
            'password' => 'required|max:500'
        ]);

        $request->merge(['distributor_percent' => 0]);
        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funCreate($request);
        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            // $a = send_sms($dataRes->data->get_user->cellphone, 'Su código de confirmacion es ' . $dataRes->data->get_user->code);
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
    public function getDistributor(Request $request){
         
        $distributor = Distributor::where('distributor_code', $request->distributor_code)
            ->where('state', 1)
            ->first();
        if (!empty($distributor)){
            return response()->json(['code' => 200, 'message' => 'Distribuidor válido', 'data' => $distributor]);
        }
        return response()->json(['code' => 400, 'message' => 'Distribuidor inactivo', 'data' => $distributor]);
    }
    public function getDistributorByOrder($id){
         
        $distributor = DistributorComissions::where('order_id', $id)
            ->where('state', 1)
            ->first();
        if (!empty($distributor)){
            return response()->json(['code' => 200, 'message' => 'Con distribuidor', 'data' => $distributor]);
        }
        return response()->json(['code' => 400, 'message' => 'Sin distribuidor', 'data' => $distributor]);
    }

}
