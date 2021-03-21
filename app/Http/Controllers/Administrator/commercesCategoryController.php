<?php

namespace App\Http\Controllers\Administrator;

use App\Commerce;
use App\commercesCategories;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\ParameterValue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class commercesCategoryController extends Controller
{

    public function index()
    {
        request()->merge(['id' => 0]);
        $categories = $this->funGetList(request())->original['data'];
        $commerceType = ParameterValue::where('parameter_id', 4)->get();
        if (!is_null(request()->name)) {
            $categories = $categories->filter(function ($cat) {
                return false !== stripos($cat->name, request()->name);
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $categories = $categories->where('state', request()->state);
        }
        return view('commercesCategories.show', ['categories' => $categories, 'type' => $commerceType]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ParameterValue::where('parameter_id', 6)->get();
        $roles = ParameterValue::where('parameter_id', 2)->get();
        $data = array(
            'DeliveryConfig' => $types,
            'commerce_type' => $roles
        );

        return view('commerces.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = $this->funCreate($request)->getData();
        if ($store->code == 200) {
            Session::flash('success', 'Creado con éxito');
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
        $commerce = $this->funShow(request())->original['data'];
        $commerceType = ParameterValue::where('parameter_id', 4)->get();
        return view('commercesCategories.show', ['commerce' => $commerce, 'type' => $commerceType]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        request()->merge(['id' => $id]);
        $category = $this->funShow(request())->original['data'];
        $commerceType = ParameterValue::where('parameter_id', 4)->get();
        return view('commercesCategories.edit', ['category' => $category, 'type' => $commerceType]);
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

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = commercesCategories::where('state', '<>', 2)->get();
        } else {
            $list = commercesCategories::where('id', $id)->where('state', '<>', 2)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetcategory(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = Commerce::where('state', 1)->with('getCategories')->get();
        } else {
            $list = Commerce::where('id', $id)->where('state', 1)->with('getCategories')->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function listCategoriesCommerce(request $request)
    {
        $id = $request->id;
        $addresses = [];

        if ($id == 0) {
            $list = commercesCategories::where('state', 1)->with('getCommerces.getUser')->get();
        } else {
            $list = commercesCategories::where('id', $id)->where('state', 1)->with('getCommerces.getUser')->get();
        }

        if (!is_null($request->lat) || !is_null($request->lng)) {

            foreach ($list as $categories) {
                foreach ($categories->getCommerces as $commerce) {
                    if ($commerce->delivery_config == 13) {
                        $address = $commerce->getUser->getCommerceAddress;
                        if (!is_null($address) && $address->count() > 0) {
                            $lat = $address->lat;
                            $lng = $address->lng;
                            $addresses[$commerce->id] = [
                                'commerce_id' => $commerce->id,
                                ['lat' => $request->lat, 'lng' => $request->lng],
                                ['lat' => $lat, 'lng' => $lng]
                            ];
                        }
                    }
                }
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post(config('app.domiciliosApp') . 'api/getConfigsByGroup', [
                'addresses' => $addresses
            ]);

            if ($response->status() == 200) {
                $data = $response->json()['data'];
                foreach ($data as $id => $value) {
                    foreach ($list as $categories) {
                        $commerce = $categories->getCommerces->find($id);
                        if (!is_null($commerce)) {
                            $commerce->delivery_value = $value['delivery_value'];
                        }
                    }
                }
            }
        }

        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetCategoriesByCommerceType(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $categories = commercesCategories::where('state', 1)
            ->where('commerce_type', $request->id)
            ->get();

        return response()->json(['code' => 200, 'data' => $categories], 200);
    }

    public function getCategoriesCommerce(request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = commercesCategories::where('state', 1)->with('getCommerces')->first();
        } else {
            $list = commercesCategories::where('id', $id)->where('state', 1)->with('getCommerces')->first();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:commerce_categories,id'
        ]);
        $element = commercesCategories::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }
    public function funShowCategory(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:commerces,id'
        ]);
        $element = Commerce::where('id', $request->id)->first()->getCategories;
        return response()->json(['code' => 200, 'data' => $element], 200);
    }
    public function funCreate(Request $request)
    {

        $request->validate([
            'name' => 'required|max:499',
            // 'photo' => 'required|image|mimes:jpg,jpeg,png',
            'description' => 'required|max:499',
            'commerce_type_vp' => 'exists:parameter_values,id'
        ]);

        $newCategory = new commercesCategories();
        $newCategory->name = $request->name;
        $newCategory->description = $request->description;
        $newCategory->commerce_type = $request->commerce_type_vp;
        // if ($request->photo) {
        // $newCategory->photo = Carbon::now()->timestamp . "." . $request->photo->getClientOriginalExtension();
        //     $request->photo->storeAs('categories_img/',  $newCategory->photo, 'public');
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
            'id' => 'required|exists:commerce_categories,id',
            'name' => 'required|max:499',
            // 'photo' => 'image|mimes:jpg,jpeg,png',
            'description' => 'required|max:499',
            'commerce_type_vp' => 'exists:parameter_values,id',
            'state' => 'required'
        ]);

        $Category = commercesCategories::where('id', $request->id)->first();
        $Category->name = $request->name;
        // if ($request->hasFile('photo')) {
        //     Storage::disk("public")->delete("categories_img/" . $Category->photo);
        //     $Category->photo = Carbon::now()->timestamp . "." . $request->photo->getClientOriginalExtension();
        //     $request->photo->storeAs('categories_img/',   $Category->photo, 'public');
        // }
        $Category->description = $request->description;
        $Category->commerce_type = $request->commerce_type_vp;
        $Category->state = $request->state;

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

        $model = commercesCategories::where('id', $request->id)->first();
        $model->state = 2;

        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}
