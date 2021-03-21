<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\ParameterValue;
use Illuminate\Http\Request;

class ParameterValuesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //request()->merge(['id' => $id]);
        // $list = $this->funGetList(request())->data;
        // return view('parameter_value.index', compact('list'));
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
        $response = $this->funCreate($request);
        $code = $response->code;
        if ($code == 200) {
            return view('parameter_values.create', compact('response'));
        } else {
            Session::flash('Ocurrio un error', compact('response'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->merge(['id' => $id]);
        $store = $this->funUpdate($request)->getData();

        if ($store->code == 200) {
            $request->session()->flash('success', 'actualizado con exito');
            return redirect()->route('parameter_values.index');
        }
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

    /**** FUNCIONES *****/

    public function funGetList(Request $request)
    {
        $request->validate([
            'parameter_id' => 'exclude_if:parameter_id,0|required|exists:parameters,id'
        ]);

        $id = $request->parameter_id;
        if ($id == 0) {
            $list = ParameterValue::where('state', 1)->get();
        } else {
            $list = ParameterValue::where('parameter_id', $request->parameter_id)->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'parameter_id' => 'required|string|min:6|max:50',
            'name' => 'required|string',
            'extra' => 'required|string',
            'state' => 'required|max:11'
        ]);
        $data = $request->input();
        $parameter = new ParameterValue($data);
        if ($parameter->save()) {
            return response()->json(['code' => 200, 'data' => $parameter], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:parameter_values,id',
            'parameter_id' => 'required|string|min:6|max:50',
            'name' => 'required|string',
            'extra' => 'required|string',
            'state' => 'required|max:11'
        ]);
        $parameter = ParameterValue::find($request->id);
        $data = $request->input();

        if ($parameter->update($data)) {
            return response()->json(['code' => 200, 'data' => $parameter], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:parameter_values,id'
        ]);

        $model = ParameterValue::where('id', $request->id)->first();
        $model->state = 2;
        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}
