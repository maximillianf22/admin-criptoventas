<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsorderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user->rol_vp == 'admin'){
            $id = 0;
        }else{
            $id = 1;
        }
        request()->merge(['id' => $id]);
        $list = $this->funGetList(request())->data;
        return view('admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if($id == 0){
            $list = OrderDetail::where('state', 1)->get();
        }else{
            $list = OrderDetail::where('id', $id)->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id'
        ]);

        $id = $request->id;

        if ($id == 0) {
            $list = OrderDetail::where('state', 1)->get();
        } else {
            $list = OrderDetail::where('order_id', $id)->where('state', 1)->get();
        }

        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'searchID' => 'required|exists:order_details,id'
        ]);

        $order = OrderDetail::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $order], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|max:255',
            'order_id' => 'required|numeric|max:255',
            'name' => 'required|string',
            'value' => 'required|numeric|max:255',
            'quantity' => 'numeric',
            'total_value' => 'required|numeric|max:255',
            'observation' => 'required|max:255',
            'product_config' => 'required|string|max:255',
            'state' => 'numeric',
        ]);

        $details = new OrderDetail();
        $details->product_id = $request->product_id;
        $details->order_id = $request->order_id;
        $details->name = $request->name;
        $details->value = $request->value;
        $details->total_value = $request->total_value;
        $details->observation = $request->observation;
        $details->product_config = $request->product_config;
        $details->state = $request->state;


        if($details->save()){
            return response()->json(['code' => 200, 'data' => $details], 200);
        }else{
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|max:255',
            'order_id' => 'required|numeric|max:255',
            'name' => 'required|string',
            'value' => 'required|numeric|max:255',
            'quantity' => 'numeric',
            'total_value' => 'required|numeric|max:255',
            'observation' => 'required|max:255',
            'product_config' => 'required|string|max:255',
            'state' => 'numeric',
        ]);

        $details = OrderDetail::where('id', $request->id);
        $details->product_id = $request->product_id;
        $details->order_id = $request->order_id;
        $details->name = $request->name;
        $details->value = $request->value;
        $details->total_value = $request->total_value;
        $details->observation = $request->observation;
        $details->product_config = $request->product_config;
        $details->state = $request->state;

        if($details->update()){
            return response()->json(['code' => 200, 'data' => $details], 200);
        }else{
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:order_details,id'
        ]);

        $model = OrderDetail::where('id', $request->id)->first();
        $model->state = 2;
        if($model->update()){
            return response()->json(['code' => 200, 'data' => $model], 200);
        }else{
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}