<?php

namespace App\Http\Controllers\ApiControllers;

use App\cupones;
use App\Http\Controllers\Administrator\CuponesController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponsController extends CuponesController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCoupon(Request $request)
    {
        $request->validate([
            'name' => 'exists:coupons,name',
            'commerce_id' => 'exists:commerces,id'
        ]);
        $cupon = cupones::where('name', $request->name)
            ->where('commerce_id', $request->commerce_id)
            ->where('max_quantity', '>=', 0)
            ->where('state', 1)
            ->first();
        if (!empty($cupon)){
            if($cupon->max_quantity==0)
                return  response()->json(['code'=>400,'message'=>'Cupon agotado']);
            if ($cupon->max_quantity>0)
                return response()->json(['code' => 200, 'message' => 'Cupon valido', 'data' => $cupon]);
        }


        return response()->json(['code' => 400, 'message' => 'cupon inactivo o invalido ', 'data' => $cupon]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
