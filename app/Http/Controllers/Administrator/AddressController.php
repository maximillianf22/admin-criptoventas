<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            return back()->with('success', 'Dirección creada correctamente');
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
        $update = $this->funUpdate($request)->original['code'];
        if ($update == 200) {
            return back()->with('success', 'Dirección actualizada correctamente');
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
        $deleted = $this->funDelete(request())->original['code'];
        if ($deleted == 200) {
            return back()->with('success', 'Dirección eliminada correctamente');
        } else {
            return back();
        }
    }

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $addresses = UserAddress::where('state', '<>', 2)->get();
        return response()->json(['code' => 200, 'data' => $addresses], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $address = UserAddress::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $address], 200);
    }

    public function funShowByUser(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $addresses = UserAddress::where('user_id', $request->id)->where('state', 1)->get();
        return response()->json(['code' => 200, 'data' => $addresses], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|max:499',
            'address' => 'required|max:499',
            'lat' => 'required|max:22',
            'lng' => 'required|max:22',
            'observation' => 'max:499',
        ]);

        $address = new UserAddress();
        $address->user_id = $request->user_id;
        $address->name = $request->name;
        $address->address = $request->address;
        $address->lat = $request->lat;
        $address->lng = $request->lng;
        $address->observation = $request->observation;

        if ($address->save()) {
            return response()->json(['code' => 200, 'data' => $address], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_addresses,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|max:499',
            'address' => 'required|max:499',
            'lat' => 'required|max:22',
            'lng' => 'required|max:22',
            'observation' => 'max:499',
        ]);

        $address = UserAddress::where('id', $request->id)->first();
        $address->name = $request->name;
        $address->address = $request->address;
        $address->lat = $request->lat;
        $address->lng = $request->lng;
        $address->observation = $request->observation;

        if ($address->update()) {
            return response()->json(['code' => 200, 'data' => $address], 200);
        } else {
            return response()->json(['code' => 530, 'data' => $address, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_addresses,id'
        ]);

        $address = UserAddress::where('id', $request->id)->first();
        $address->state = 2;
        if ($address->save()) {
            return response()->json(['code' => 200, 'data' => $address], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}
