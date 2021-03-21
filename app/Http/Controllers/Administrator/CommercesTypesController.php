<?php

namespace App\Http\Controllers\Administrator;

use App\Commerce;
use App\commercesCategories;
use App\Http\Controllers\Controller;
use App\ParameterValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;

class CommercesTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $parameters = ParameterValue::where('parameter_id', 4)->get();
        return view('commercetype.index', compact('parameters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $response = $this->funCreate($request);
        $code = $response->code;
        if ($code == 200) {
            return view('commercetype.create', compact('response'));
        } else {
            FacadesSession::flash('Ocurrio un error', compact('response'));
            return back();
        }
    }
    public function categoriesShow($id, Request $request)
    {
        $request->validate(['id' => 'exists:commerces,id']);
        $commerce = Commerce::find($request->id);
        $categories = commercesCategories::all();
        return view('commerces.categories', compact('categories', 'commerce'));
    }
    public function categoriesStore(Request $request)
    {
        $request->validate([
            'category_id' => 'exists:commerce_categories,id',
            'commerce' => 'exists:commerces,id'
        ]);
        $commerce = Commerce::find($request->commerce);
        $commerce->getCategories()->attach($request->category_id);
        return back();
    }
    public function categoriesDelete(Request $request)
    {

        $request->validate([
            'category_id' => 'exists:commerce_categories,id',
            'commerce' => 'exists:commerces,id'
        ]);

        $commerce = Commerce::find($request->commerce);
        if ($commerce->getCategories()->detach($request->category_id)) {
            # code...
            return response()->json(['code' => 200, 'data' => $commerce], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
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
        request()->merge(['id' => $id]);
        $store = $this->funUpdate($request)->getData();

        if ($store->code == 200) {
            $request->session()->flash('success', 'actualizado con exito');
            return redirect()->route('commercetype.index');
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

    /********** FUNCIONES ***********/

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
