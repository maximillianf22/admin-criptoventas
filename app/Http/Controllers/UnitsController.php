<?php

namespace App\Http\Controllers;

use App\Commerce;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id == 1) {
            request()->merge(['id' => 0]);
        } else {
            request()->merge(['id' => $user->getCommerce->id]);
        }

        $units = $this->funGetList(request())->original['data'];
        $commerces = Commerce::where('state', 1)->get();
/*
        if (!is_null(request()->name)) {
            $units = $units->filter(function ($unit) {
                return $unit->name == request()->name;
            });
        } */

        if (!is_null(request()->name)) {
            $units = $units->filter(function ($unit) {
                return false !== stripos($unit->name, request()->name);
            });
        }

        if (!is_null(request()->bussiness_name)) {
            $units = $units->filter(function ($unit) {
                return false !== stripos($unit->getCommerce->bussiness_name, request()->bussiness_name);
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $units = $units->where('state', request()->state);
        }

        $data = array(
            'units' => $units,
            'commerces' => $commerces
        );
        return view('units.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $store = $this->funCreate($request)->original['code'];
        if ($store == 200) {
            return back();
        } else {
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        request()->merge(['id' => $id]);
        return $this->funShow(request());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
            return redirect()->route('units.index');
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
        request()->merge(['id' => $id]);
        return $this->funDelete(request())->original['code'];
    }


    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        if ($request->id == 0) {
            $list = Unit::where('state', '<>', 2)->get();
        } else {
            $list = Unit::where('commerce_id', $request->id)->where('state', '<>', 2)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:units,id'
        ]);

        $element = Unit::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|numeric|exists:commerces,id',
            'name' => 'required|string',
        ]);
        
        $units = new Unit();
        $units->commerce_id = $request->commerce_id;
        $units->name = $request->name;

        if ($units->save()) {
            return response()->json(['code' => 200, 'data' => $units], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }
    public function funUpdate(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|numeric|max:11',
            'name' => 'required|string',
            'state' => 'numeric'
        ]);
        $units = Unit::find($request->id);
        $units->commerce_id = $request->commerce_id;
        $units->name = $request->name;
        $units->state = $request->state;
        if ($units->update()) {
            return response()->json(['code' => 200, 'data' => $units], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:units,id'
        ]);

        $units = Unit::where('id', $request->id)->first();
        $units->state = 2;
        if ($units->update()) {
            return response()->json(['code' => 200, 'data' => $units], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}