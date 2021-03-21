<?php

namespace App\Http\Controllers;

use App\Commerce;
use App\Tips;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id != 2) {
            request()->merge(['id' => 0]);
        } else {
            request()->merge(['id' => $user->getCommerce->id]);
        }

        $tips = $this->funGetList(request())->original['data'];

        if (!is_null(request()->value)) {
            $tips = $tips->filter(function ($tip) {
                return $tip->value == request()->value;
            });
        }
        if (!is_null(request()->bussiness_name)) {
            $tips = $tips->filter(function ($tip) {
                return false !== stripos($tip->getCommerce->bussiness_name, request()->bussiness_name);
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $tips = $tips->where('state', request()->state);
        }

        $commerces = Commerce::where('state', 1)->get();
        $data = array(
            'tips' => $tips,
            'commerces' => $commerces
        );
        return view('tips.index', $data);
    }

    public function create()
    {
        return view('tips.create');
    }

    public function store(Request $request)
    {
        $tips = $this->funCreate($request)->original['code'];
        if ($tips == 200) {
            return back()->with('success', 'Propina creada existosamente');
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
        $store = $this->funUpdate($request)->getData();
        if ($store->code == 200) {
            $request->session()->flash('succes', 'actualizado con exito');
            return redirect()->route('tips.index');
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
        $id = $request->id;
        if ($id == 0) {
            $list = Tips::where('state', '<>', 2)->get();
        } else {
            $list = Tips::where('commerce_id', $id)->where('state', '<>', 2)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByCommerce(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:commerces,id'
        ]);

        $id = $request->id;
        if ($id == 0) {
            $list = Tips::where('state', 1)->get();
        } else {
            $list = Tips::where('commerce_id', $id)->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tips,id'
        ]);

        $element = Tips::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|numeric|exists:commerces,id',
            'value' => 'required|numeric|max:255',
        ]);

        $tips = new Tips();
        $tips->commerce_id = $request->commerce_id;
        $tips->value = $request->value;

        if ($tips->save()) {
            return response()->json(['code' => 200, 'data' => $tips], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|numeric|max:11',
            'value' => 'required|numeric|max:255',
            'state' => 'numeric'
        ]);

        $tips = Tips::find($request->id);
        $tips->commerce_id = $request->commerce_id;
        $tips->value = $request->value;
        $tips->state = $request->state;

        if ($tips->update()) {
            return response()->json(['code' => 200, 'data' => $tips], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tips,id'
        ]);

        $tips = Tips::where('id', $request->id)->first();
        $tips->state = 2;
        if ($tips->update()) {
            return response()->json(['code' => 200, 'data' => $tips], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}