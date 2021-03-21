<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $list = Model::where('state', 1)->get();
        }else{
            $list = Model::where('id', $id)->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'searchID' => 'required|exists:table,id'
        ]);

        $element = Model::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'param1' => 'required|max:255',
            'param2' => 'required|numeric|max:255',
            'param3' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        $newModel = new Model();
        $newModel->param1 = $request->param1;
        $newModel->param2 = $request->param2;
        $newModel->param3 = $request->param3;

        if($newModel->save()){
            return response()->json(['code' => 200, 'data' => $newModel], 200);
        }else{
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:table,id',
            'param1' => 'required|max:255',
            'param2' => 'required|numeric|max:255',
            'param3' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        $modal = Model::where('id', $request->id);
        $modal->param1 = $request->param1;
        $modal->param2 = $request->param2;
        $modal->param3 = $request->param3;

        if($modal->update()){
            return response()->json(['code' => 200, 'data' => $modal], 200);
        }else{
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:table,id'
        ]);

        $model = Model::where('id', $request->id)->first();
        $model->state = 2;
        if($model->update()){
            return response()->json(['code' => 200, 'data' => $model], 200);
        }else{
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}
