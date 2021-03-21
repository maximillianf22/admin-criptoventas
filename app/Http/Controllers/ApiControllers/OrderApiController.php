<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\OrdersController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends OrdersController
{
    public function postOrder(Request $request)
    {
        $Validation = Validator::make($request->input(), [
            'commerce_id' => 'required|exists:commerces,id',
            'customer_id' => 'required|exists:customers,id',
            //'reference' => 'required|exists:orders, id', //Reference
            //'date' => 'required|exists:orders, id',
            'payment_type_vp' => 'required',
            //'payment_state' => '',
            'name' => 'nullable|exists:coupons,name',
            'delivery_value' => 'required|numeric',
            'user_address_id' => 'required|exists:user_addresses,id',
            'order_details' => 'required'
        ]);
        if ($request->has('name')) {
            $cuponController = new CouponsController();
            $validCupon = $cuponController->getCoupon($request)->original;

            if ($validCupon['code'] != 200)
                return response()->json([
                    'code' => 400,
                    'data' => null,
                    'message' => 'cupon invalido'
                ], 400);

            $request->merge(['coupon_value' => $validCupon['data']['value'], 'coupon_id' => $validCupon['data']['id']]);
        }
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
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

    public function getOrderDetail(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'id' => 'required|exists:orders,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funShow($request);
        $dataRes = $response->getData();

        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

    public function getListOrder(Request $request)
    {

        $Validation = Validator::make($request->all(), [
            'id' => 'required|exists:customers,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->funGetListByCustomer($request);

        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }
}
