<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Distributor;
use App\Http\Controllers\Administrator\CustommerController;
use App\ParameterValue;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomersApiController extends CustommerController
{
    public function postRegister(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            // 'document' => 'required|string|min:6|max:500|unique:users,document',
            // 'document_type_vp' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10|unique:users,cellphone',
            'password' => 'required|max:500|confirmed',
        ]);

        if ($request->distributor_code != null && !empty($request->distributor_code)) {
            $distributorCode = Distributor::where('distributor_code', $request->distributor_code)->first();
            if (empty($distributorCode)) {
                return response()->json([
                    'code' => 400,
                    'data' => null,
                    'message' => 'No se encuentra el distribuidor con el codigo indicado'
                ], 400);
            }else{
                $request->merge(['distributor_id' => $distributorCode->id]);
            }
        }

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        // if (!$this->verifyDocType_vp($request->document_type_vp)) {
        //     return response()->json([
        //         'code' => 400,
        //         'data' => null,
        //         'message' => 'No se reconoce id del tipo de documento'
        //     ], 400);
        // }
        $request->merge(['profile_id' => 3]);

        $response = $this->funCreate($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            //$a = send_sms($dataRes->data->get_user->cellphone, 'Su código de confirmacion es ' . $dataRes->data->get_user->code, 1);
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

    public function postEditProfile(Request $request)
    {

        $customer = Customer::find($request->id);

        $Validation = Validator::make($request->all(), [
            'id' => 'required|exists:customers,id',
            // 'document' => 'required|string|min:6|max:500|unique:users,document,' . $customer->getUser->id,
            // 'document_type_vp' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10|unique:users,cellphone,' . $customer->getUser->id,
            'password' => 'nullable|min:6|max:500',
            'user_state' => 'required|numeric|max:11',
            'distributor_id' => 'numeric|max:11|exists:distributors,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        // if (!$this->verifyDocType_vp($request->document_type_vp)) {
        //     return response()->json([
        //         'code' => 400,
        //         'data' => null,
        //         'message' => 'No se reconoce id del tipo de documento'
        //     ], 400);
        // }

        $request->merge(['profile_id' => 3]);

        $response = $this->funUpdate($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

    public function verifyDocType_vp($var)
    {
        if (!empty($var) && $var != null) {
            $doctypes = ParameterValue::where('parameter_id', 1)->where('id', $var)->get();
            if ($doctypes->count() == 0) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    public function confirmCode(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'cellphone' => 'required|exists:users,cellphone',
            'code' => 'required|exists:users,code'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }
        $user = User::where('cellphone', $request->cellphone)->first();
        $user->user_state = 1;
        $user->code_confirmed = 1;
        $user->update();
        return response()->json(['code' => 200, 'data' => null], 200);
    }

    public function sendCode(Request $request)
    {
        $res = $this->funSendCode($request);
        return $res;
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'cellphone' => 'required|exists:users,cellphone',
                'password' => 'required'
            ]);

            $user = User::where('cellphone', $request->cellphone)->first();
            $user->password = bcrypt($request->password);
            $user->update();

            return response()->json(['code' => 200, 'data' => 'ok'], 200);
        } catch (\Throwable $th) {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }
}
