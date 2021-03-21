<?php

namespace App\Http\Controllers\Administrator;

use App\Commerce;
use App\cupones;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuponesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id != 2) {
            request()->merge(['id' => 0]);
        } else {
            request()->merge(['id' => $user->getCommerce->id]);
        }
        $commerces = Commerce::where('state', 1)->get();
        $cupones = $this->funGetList(request())->original['data'];


        if (!is_null(request()->name)) {
            $cupones = $cupones->filter(function ($cupone) {
                return false !== stripos($cupone->name, request()->name);
            });
        }
        if (!is_null(request()->bussiness_name)) {
            $cupones = $cupones->filter(function ($cupone) {
                return false !== stripos($cupone->getCommerce->bussiness_name, request()->bussiness_name);
            });
        }

        if (!is_null(request()->max_quantity)) {
            $cupones = $cupones->filter(function ($cupone) {
                return $cupone->max_quantity == request()->max_quantity;
            });
        }
        if (!is_null(request()->min_shopping)) {
            $cupones = $cupones->filter(function ($cupone) {
                return $cupone->min_shopping == request()->min_shopping;
            });
        }
        if (!is_null(request()->value)) {
            $cupones = $cupones->filter(function ($cupone) {
                return $cupone->value == request()->value;
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $cupones = $cupones->where('state', request()->state);
        }

        $data = array(
            'cupones' => $cupones,
            'commerces' => $commerces
        );
        return view('coupons.index', $data);

/*         return view('coupons.index', ['commerces' => $commerces, 'cupones' => $cupones['data']]);
 */    }

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
        $res = $this->funCreate($request)->original;
        if ($res['code'] == 200)
            session()->flash('success', 'cupon creado con exito');
        if ($res['code'] == 469)
            session()->flash('wrong', 'Cupon existente para ese comercio');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cupones  $cupones
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
     * @param  \App\cupones  $cupones
     * @return \Illuminate\Http\Response
     */
    public function edit(cupones $cupones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cupones  $cupones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->merge(['id' => $id]);
        $store = $this->funUpdate($request)->getData();
        if ($store->code == 200) {
            $request->session()->flash('succes', 'actualizado con exito');
            return redirect()->route('coupons.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cupones  $cupones
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
            $list = cupones::where('state', '<>', 2)->get();
        } else {
            $list = cupones::where('commerce_id', $id)->where('state', '<>', 2)->get();
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
            $list = cupones::where('state', 1)->get();
        } else {
            $list = cupones::where('commerce_id', $id)->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id'
        ]);

        $element = cupones::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'commerce_id' => 'required|numeric|exists:commerces,id',
            'value' => 'required|numeric|min:1000',
            'max_quantity' => 'required|numeric|min:1',
            'min_shopping' => 'required|numeric',

        ]);
        $valid = cupones::where('commerce_id', $request->commerce_id)
            ->where('name', $request->name)
            ->where('state', '<>', [2])
            ->first();

        if (empty($valid)) {
            $cupones = new cupones();
            $cupones->name = $request->name;
            $cupones->commerce_id = $request->commerce_id;
            $cupones->value = $request->value;
            $cupones->min_shopping = $request->min_shopping;
            $cupones->max_quantity = $request->max_quantity;

            if ($cupones->save()) {
                return response()->json(['code' => 200, 'data' => $cupones], 200);
            } else {
                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
            }
        } else {
            return response()->json(['code' => 469, 'data' => null, 'message' => 'Error al crear'], 469);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'exists:coupons,id',
            'name' => 'required|exists:coupons,name',
            'commerce_id' => 'required|numeric|max:11|exists:commerces,id',
            'value' => 'required|numeric|min:1000',
            'max_quantity' => 'required|numeric|min:1',
            'min_shopping' => 'required|numeric',

        ]);

        $cupones = cupones::find($request->id);
        $cupones->name = $request->name;
        $cupones->commerce_id = $request->commerce_id;
        $cupones->value = $request->value;
        $cupones->min_shopping = $request->min_shopping;
        $cupones->max_quantity = $request->max_quantity;
        $cupones->state = $request->state;

        if ($cupones->update()) {
            return response()->json(['code' => 200, 'data' => $cupones], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id'
        ]);

        $cupones = cupones::where('id', $request->id)->first();
        $cupones->state = 2;
        if ($cupones->update()) {
            return response()->json(['code' => 200, 'data' => $cupones], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}