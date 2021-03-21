<?php

namespace App\Http\Controllers\Commerce;

use App\Commerce;
use App\Http\Controllers\Controller;
use App\Rol;
use App\ProfileMin;
use Illuminate\Http\Request;

class MinimumShoppingValueController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $commerce = Commerce::where('state', 1)->get();
    $perfiles = Rol::where('state', 1)->where('unique', 0)->get();
    $data = array(
      'perfiles' => $perfiles,
      'commerce' => $commerce,
    );
    return view('minimunshoppingvalue.index', $data);
  }

  public function showByCommerce(Request $request)
  {
    $mins = $this->funShowByCommerce($request);
    if ($mins->original['code'] == 200) {
      return $mins;
    } else {
      return response()->json(['code' => 530, 'data' => null], 530);
    }
  }

  public function store(Request $request)
  {
    // dd($request->all());
    $store = $this->funCreate($request);
    if ($store->original['code'] == 200) {
      return response()->json(['code' => 200, 'data' => $store->original['data']], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null], 530);
    }
  }

  public function minCompra()
  {
    $commerce = Commerce::where('state', 1)->get();
    $perfiles = ProfileMin::where('state', 1)->get();
    return view('minimunshoppingvalue.index', compact('perfiles', 'commerce'));
  }

  public function saveMinCompra(Request $request)
  {
    $minimos = $request->minimos;
    $perfiles = ProfileMin::where('state', 1)->get();
    foreach ($perfiles as $perfil) {
      $perfil->value = $minimos[$perfil->id];
      /*  dd($minimos); */
      $perfil->save();
    }
    return redirect()->route('admin.minCompra');
  }

  public function show($id)
  {
    //
  }

  public function loadItemCar()
  {
  }

  public function destroy($id)
  {
  }

  //****************** FUNCIONES ************************/

  public function funShowByCommerce(Request $request)
  {
    $request->validate([
      'commerce_id' => 'required|exists:commerces,id'
    ]);
    $mins = ProfileMin::where('commerce_id', $request->commerce_id)->where('state', 1)->get();
    return response()->json(['code' => 200, 'data' => $mins], 200);
  }

  public function funCreate(Request $request)
  {
    // dd($request->all());
    $request->validate([
      'commerce_id' => 'required|exists:commerces,id',
      'minimos' => 'required|array'
    ]);

    $commerce = Commerce::where('id', $request->commerce_id)->first();

    foreach ($request->minimos as $id => $value) {
      ProfileMin::updateOrCreate(
        ['commerce_id' => $request->commerce_id, 'profile_vp' => $id],
        ['value' => $value]
      );
    }
    return response()->json(['code' => 200, 'data' => $commerce->getMinList], 200);
  }
}
