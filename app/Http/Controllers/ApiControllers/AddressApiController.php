<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressApiController extends AddressController
{
    public function getAddressDetails(Request $request){
        $Validation = Validator::make($request->all(),[
            'id' => 'required|exists:user_addresses,id'
               ]);

        if ($Validation->fails()){
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()], 400);
        }

        $response = $this->funShow($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        }else{
            return $response;
        }

    }

    public function getListAddress(Request $request){
        $Validation = Validator::make($request->all(),[
            'id' => 'required|exists:users,id'
               ]);

        if ($Validation->fails()){
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()], 400);
        }

        $response = $this->funShowByUser($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        }else{
            return $response;
        }

    }

    public function postDeleteAddress(Request $request){
        $Validation = Validator::make($request->all(),[
            'id' => 'required|exists:user_addresses,id'
            ]);

        if ($Validation->fails()){
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()], 400);
        }

        $response = $this->funDelete($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        }else{
            return $response;
        }

    }

    public function postUpdateAddress(Request $request){
        $Validation = Validator::make($request->all(),[
            'id' => 'required|exists:user_addresses,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|max:499',
            'address' => 'required|max:499',
            'lat' => 'required|max:22',
            'lng' => 'required|max:22',
            'observation' => 'max:499'
            ]);

        if ($Validation->fails()){
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()], 400);
        }

        $response = $this->funUpdate($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        }else{
            return $response;
        }
    }

    public function postAddAddress(Request $request){
        $Validation = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'name' => 'required|max:499',
            'address' => 'required|max:499',
            'lat' => 'required|max:22',
            'lng' => 'required|max:22',
            'observation' => 'max:499',
            ]);

        if ($Validation->fails()){
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()], 400);
        }

        $response = $this->funCreate($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        }else{
            return $response;
        }
    }

}