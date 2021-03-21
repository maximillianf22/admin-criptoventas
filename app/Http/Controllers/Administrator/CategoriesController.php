<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Category;
use App\Commerce;
use App\commercesCategories;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Session;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $user = Auth::user();
        if ($user->rol_id == 2) {
            request()->merge(['id' => $user->getCommerce->id]);
        } else {
            request()->merge(['id' => 0]);
        }
        $categories = $this->funGetList(request())->original['data'];
        if (!is_null(request()->bussiness_name)) {
            $categories = $categories->filter(function ($cat) {
                return false !== stripos($cat->getCommerce->bussiness_name, request()->bussiness_name);
            });
        }
        if (!is_null(request()->name)) {
            $categories = $categories->filter(function ($cat) {
                return false !== stripos($cat->name, request()->name);
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $categories = $categories->where('state', request()->state);
        }

        $commerces = Commerce::where('state', 1)->get();
        $data = array(
            'categories' => $categories,
            'commerces' => $commerces
        );
        return view('categories.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $commerces = Commerce::where('state', 1)->get();
        $categories = [];
        if ($user->rol_id != 1) {
            $categories = Category::where('commerce_id', $user->getCommerce->id)->where('state', 1)->doesntHave('getProductCategories')->get();
        }

        $data = array(
            'commerces' => $commerces,
            'categories' => $categories
        );
        return view('categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = $this->funCreate($request);
        $code = $response->getData()->code;
        // dd($request->all());
        if ($code == 200) {
            $request->session()->flash('success', 'Creado con exito');
            return redirect()->route('categories.index');
        } else {
            Session::flash('Ocurrio un error', compact('response'));
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $request->merge(['id' => $id]);
        return $this->funShow($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commerces = Commerce::where('state', 1)->get();
        $category = Category::where('id', $id)->first();
        $categories = Category::where('commerce_id', $category->commerce_id)->where('state', 1)->doesntHave('getProductCategories')->get();

        $data = array(
            'category' => $category,
            'commerces' => $commerces,
            'categories' => $categories
        );
        return view('categories.edit', $data);
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
            return back()->with('success', 'La categoría ha sido editada existosamente');
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
    public function destroy(Request $request)
    {
        return $this->funDelete($request);
    }

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $request->validate([
            'id' => 'exclude_if:id,0|required|exists:commerces,id'
        ], [
            'id.required' => 'Debe enviar el id que desea buscar',
            'id.exists' => 'No se encontraron registros con searchID'
        ]);

        $id = $request->id;
        if ($id == 0) {
            $list = Category::where('state', '<>', 2)->get();
        } else {
            $list = Category::where('commerce_id', $id)->where('state', '<>', 2)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetCategoriesBycommerce(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:commerces,id'
        ]);

        $categories = Category::where('commerce_id', $request->id)
            ->where('state', 1)
            ->doesntHave('getProductCategories')
            ->get();

        return response()->json(['code' => 200, 'data' => $categories], 200);
    }
    public function funGetCategoriesBycommerce2(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:commerces,id'
        ]);

        $categories = Category::where('commerce_id', $request->id)
            ->where('state', 1)
            ->get();

        return response()->json(['code' => 200, 'data' => $categories], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id'
        ], [
            'id.required' => 'Debe enviar el id que desea buscar',
            'id.exists' => 'No se encontraron registros con searchID'
        ]);
        $element = Category::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {

        $request->validate([
            'name' => 'required|max:499',
            // 'img_category' => 'required|image|mimes:jpg,jpeg,png',
            'description' => 'required|max:499',
            'commerce_id' => 'required|exists:commerces,id'
        ]);

        if ($request->radioCategoryType == 'cat2') {
            $request->validate([
                'parent_id' => 'required|numeric|exists:categories,id',
            ]);
        }

        $newCategory = new Category();
        $newCategory->name = $request->name;
        $newCategory->order = 0;
        $newCategory->description = $request->description;
        $newCategory->parent = $request->parent_id;
        $newCategory->commerce_id = $request->commerce_id;
        // if ($request->img_category) {
        //     $newCategory->img_category = $request->img_category->store('categories_img', 'public');
        // }

        if ($newCategory->save()) {
            return response()->json(['code' => 200, 'data' => $newCategory], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }


    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
            'name' => 'required|max:499',
            // 'img_category' => 'image|mimes:jpg,jpeg,png',
            'order' => 'numeric',
            'state' => 'required|numeric',
            'description' => 'required|max:499',
            'commerce_id' => 'required|exists:commerces,id'
        ]);

        if ($request->radioCategoryType == 'cat2') {
            $request->validate([
                'parent_id' => 'required|numeric|exists:categories,id',
            ]);
        }

        $Category = Category::where('id', $request->id)->first();
        $Category->name = $request->name;
        $Category->order = isset($request->order)  ? $request->order : 0;
        $Category->description = $request->description;
        $Category->parent = $request->parent_id;
        $Category->commerce_id = $request->commerce_id;
        $Category->state = $request->state;

        // if ($request->file('img_category')) {
        //     Storage::disk("public")->delete($Category->img_category);
        //     $Category->img_category = $request->img_category->store('categories_img', 'public');
        // }

        if ($Category->update()) {
            return response()->json(['code' => 200, 'data' => $Category], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id'
        ]);

        $category = Category::where('id', $request->id)->first();
        // dd($category->getCategorySons);
        if ($category->getCategorySons->count() == 0) {
            $category->state = 2;
            if ($category->update()) {
                return response()->json(['code' => 200, 'data' => $category], 200);
            } else {
                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
            }
        } else {
            return response()->json(['code' => 520, 'data' => null, 'message' => 'Este producto no puede ser eliminado porque tiene subcategorias'], 520);
        }
    }
}
