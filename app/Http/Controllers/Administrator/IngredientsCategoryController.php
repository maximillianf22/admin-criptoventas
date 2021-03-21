<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\IngredientCategory;
use App\ParameterValue;
use Illuminate\Http\Request;

class IngredientsCategoryController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
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
    $store = $this->funCreate($request);
    return back();
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
    $category = $this->funShow(request());
    return $category;
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
    $request->merge(['id' => $id]);
    $update = $this->funUpdate($request);
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
    $delete = $this->funDelete(request());
    return $delete;
  }

  /********** FUNCIONES ***********/

  public function funGetList(Request $request)
  {
    $id = $request->id;
    if ($id == 0) {
      $list = IngredientCategory::where('state', 1)->get();
    } else {
      $list = IngredientCategory::where('id', $id)->where('state', 1)->get();
    }
    return response()->json(['code' => 200, 'data' => $list], 200);
  }

  public function funShow(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:ingredient_categories,id',
    ]);
    $element = IngredientCategory::where('id', $request->id)->first();
    return response()->json(['code' => 200, 'data' => $element], 200);
  }

  public function ProductIngredient(Request $request)
  {
    $request->validate([
      'restaurant_product_id' => 'required|exists:ingredient_categories,restaurant_product_id'
    ]);
    $ingredients = [];
    $idParam = 3;
    $tiposIngredientes = ParameterValue::where('parameter_id', $idParam)->get(['id', 'name']);
    foreach ($tiposIngredientes as $key => $value) {
      $value->categorias = IngredientCategory::where('restaurant_product_id', $request->restaurant_product_id)
        ->where('category_type_vp', $value->id)
        ->where('state', 1)
        ->with('getIngredients')->get();
    }


    //$element = IngredientCategory::where('restaurant_product_id', $request->restaurant_product_id)->with('getIngredients')->get();
    return response()->json(['code' => 200, 'data' => $tiposIngredientes], 200);
  }

  public function funCreate(Request $request)
  {
    $request->validate([
      'restaurant_product_id' => 'required|numeric',
      'name' => 'required|string',
      'max_ingredients' => 'required|numeric',
      'category_type_vp' => 'required|numeric',
    ]);
    $ingredient = new IngredientCategory();
    $ingredient->restaurant_product_id = $request->restaurant_product_id;
    $ingredient->name = $request->name;
    $ingredient->max_ingredients = $request->max_ingredients;
    $ingredient->category_type_vp = $request->category_type_vp;
    if ($ingredient->save()) {
      return response()->json(['code' => 200, 'data' => $ingredient], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
    }
  }

  public function funUpdate(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:ingredient_categories,id',
      'name' => 'required|string',
      'max_ingredients' => 'required|numeric',
    ]);
    $ingredient = IngredientCategory::find($request->id);
    $ingredient->name = $request->name;
    $ingredient->max_ingredients = $request->max_ingredients;
    if ($ingredient->update()) {
      return response()->json(['code' => 200, 'data' => $ingredient], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
    }
  }

  public function funDelete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:ingredient_categories,id'
    ]);

    $model = IngredientCategory::where('id', $request->id)->first();
    $model->state = 2;
    if ($model->update()) {
      return response()->json(['code' => 200, 'data' => $model], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
    }
  }
}
