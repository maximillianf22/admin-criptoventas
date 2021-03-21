<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Administrator\commercesCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GlobalCategoriesApiController extends commercesCategoryController
{
    public function getCommercesCategories(Request $request)
    {
        // $Validation = Validator::make($request->all(),[
        //     'id' => 'required'
        // ]);
        // if($Validation->fails()){
        //     return response()->json([
        //         'code' => 400, 
        //         'data' => null, 
        //         'message' => $Validation->errors()->first()], 400);
        // }
        $request = request()->merge(['id' => 0]);

        $response = $this->funGetList($request);
        
        $dataRes = $response->original;
        $result = array();
        foreach ($dataRes["data"] as $item) {
            if($item->state == 1) {
                array_push($result, $item);
            }
        }
        if ($dataRes['code'] == 200) {
            return response()->json(['code' => 200, 'data' => $result], 200);
        } else {
            return $response;
        }
    }

    public function getCommercesByCategory(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'id' => 'exclude_if:id,0|required|exists:commerce_categories,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->listCategoriesCommerce($request);

        $dataRes = $response->getData();
        if ($dataRes->code == 200) {
            return response()->json(['code' => 200, 'data' => $dataRes->data], 200);
        } else {
            return $response;
        }
    }

    public function commerceToList(Request $request){

        $Validation = Validator::make($request->all(), [
            'id' => 'exclude_if:id,0|required|exists:commerce_categories,id'
        ]);

        if ($Validation->fails()) {
            return response()->json([
                'code' => 400,
                'data' => null,
                'message' => $Validation->errors()->first()
            ], 400);
        }

        $response = $this->listCategoriesCommerce($request);

        $dataRes = $response->getData();
        if ($dataRes->code == 200) {

            $commerces = $dataRes->data;
            $result = array();

            foreach ($commerces as $item) {
                //AQUI VIENE CADA ARRAY
                foreach ($item->get_commerces as $itemCommerce) {
                    if($itemCommerce->commerce_type_vp == 10) {
                        if($itemCommerce->get_user->photo == "default.png") {
                            $itemCommerce->get_user->photo = null;
                        }
                        $result[$itemCommerce->id] = $itemCommerce;
                    }
                }
            }

            sort($result);

            return response()->json(['code' => 200, 'data' => $result], 200);

        } else {
            return $response;
        }
        
    }
}
