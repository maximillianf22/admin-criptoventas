<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Ingredient;
use Illuminate\Http\Request;

class IngredientsController extends Controller
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
    $store = $this->funCreate($request);
    return $store;
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

  public function showIngredientsByCategory($id)
  {
    request()->merge(['category_id' => $id]);
    $ingredients = $this->funGetIngredientsByCategory(request());
    return $ingredients;
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
    return $this->funUpdate($request);
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

  /********** FUNCIONES ***********/

  public function funGetList(Request $request)
  {
    $id = $request->id;
    if ($id == 0) {
      $list = Ingredient::where('state', 1)->get();
    } else {
      $list = Ingredient::where('id', $id)->where('state', 1)->get();
    }
    return response()->json(['code' => 200, 'data' => $list], 200);
  }

  public function funShow(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:ingredients,id'
    ]);
    $element = Ingredient::where('id', $request->id)->first();
    return response()->json(['code' => 200, 'data' => $element], 200);
  }

  public function funGetIngredientsByCategory(Request $request)
  {
    $request->validate([
      'category_id' => 'required|exists:ingredient_categories,id'
    ]);

    $ingredients = Ingredient::where('ingredient_category_id', $request->category_id)->where('state', 1)->get();
    return response()->json(['code' => 200, 'data' => $ingredients], 200);
  }

  public function funCreate(Request $request)
  {
    $request->validate([
      'category_id' => 'required|numeric',
      'name' => 'required|string',
      'value' => 'nullable|numeric',
    ]);
    $ingredient = new Ingredient();
    $ingredient->ingredient_category_id = $request->category_id;
    $ingredient->name = $request->name;
    $ingredient->value = $request->value;
    if ($ingredient->save()) {
      return response()->json(['code' => 200, 'data' => $ingredient], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
    }
  }

  public function funUpdate(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:ingredients,id',
      'name' => 'required|string',
      'value' => 'nullable|numeric',
    ]);
    $ingredient = Ingredient::find($request->id);
    $ingredient->name = $request->name;
    $ingredient->value = $request->value;
    if ($ingredient->update()) {
      return response()->json(['code' => 200, 'data' => $ingredient], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
    }
  }

  public function funDelete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:ingredients,id',
    ]);

    $model = Ingredient::where('id', $request->id)->first();
    $model->state = 2;
    if ($model->update()) {
      return response()->json(['code' => 200, 'data' => $model], 200);
    } else {
      return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
    }
  }
}
