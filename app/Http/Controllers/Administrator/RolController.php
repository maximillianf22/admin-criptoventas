<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;

class RolController extends Controller
{

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->merge(['id' => $id]);
        $update = $this->funUpdate(request());
        if ($update->original['code'] == 200) {
            return back();
        } else {
            return back();
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
        return $this->funDelete(request());
    }

    /********* FUNCION ***********/

    public function funCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|max:499'
        ]);
        $rol = new Rol();
        $rol->name = $request->name;
        $rol->unique = 1;
        if ($rol->save()) {
            return response()->json(['code' => 200, 'data' => $rol], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:roles,id'
        ]);
        $rol = Rol::where('id', $request->id)->first();
        if (!is_null($rol)) {
            return response()->json(['code' => 200, 'data' => $rol], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:roles,id',
            'name' => 'required|max:499',
            'state' => 'required'
        ]);
        $rol = Rol::where('id', $request->id)->first();
        $rol->name = $request->name;
        $rol->state = $request->state;
        if ($rol->update()) {
            return response()->json(['code' => 200, 'data' => $rol], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:roles,id'
        ]);
        $rol = Rol::where('id', $request->id)->first();
        $rol->state = 2;
        if ($rol->update()) {
            return response()->json(['code' => 200, 'data' => $rol], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }
}