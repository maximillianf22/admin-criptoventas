<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commerce;
use App\commercesCategories;
use App\Mail\ConfirmationCommerce;
use App\ParameterValue;
use App\Rol;
use App\User;
use App\UserAddress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CreatedCommerce;
include(app_path() . '/Helpers/Helper.php');
use function GuzzleHttp\json_encode;

class CommercesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->merge(['id' => 0]);
        $commerces = $this->funGetList(request())->original['data'];
        $commerceTypes = ParameterValue::where('parameter_id', 4)->get();

        if (!is_null(request()->bussiness_name)) {
            $commerces = $commerces->filter(function ($commerce) {
                return false !== stripos($commerce->bussiness_name, request()->bussiness_name);
            });
        }
        if (!is_null(request()->nit)) {
            $commerces = $commerces->where('nit', request()->nit);
        }
        if (!is_null(request()->commerce_type) && request()->commerce_type != -1) {
            $commerces = $commerces->where('commerce_type_vp', request()->commerce_type);
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $commerces = $commerces->where('state', request()->state);
        }

        $data = array(
            'commerces' => $commerces,
            'commerceTypes' => $commerceTypes
        );
        return view('commerces.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ParameterValue::where('parameter_id', 5)->get();
        $profiles = ParameterValue::where('parameter_id', 4)->get();
        $categories = commercesCategories::where('state', 1)->get();

        $data = array(
            'DeliveryConfig' => $types,
            'commerce_type' => $profiles,
            'categories' => $categories
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
            $request->session()->flash('success', 'Creado con exito');
            return redirect()->route('commerces.index');
        } else if ($store->code == 530) {
            return back()->with('failed', 'Ocurrió un problema, intente mas tarde.');
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
        request()->merge(['id' => $id]);
        $user = $this->funShow(request())->original['data'];
        $commerce_types = ParameterValue::where('parameter_id', 4)->get();
        $config_types = ParameterValue::where('parameter_id', 5)->get();
        $categories = commercesCategories::where('state', 1)->where('commerce_type', $user->commerce_type_vp)->get();
        $data = array(
            'data' => $user,
            'DeliveryConfig' => $config_types,
            'commerce_type' => $commerce_types,
            'categories' => $categories
        );
        return view('commerces.edit', $data);
    }
    public function categoriesShow($id, Request $request)
    {
        $request->validate(['id' => 'exists:commerces,id']);
        $commerce = Commerce::find($request->id);
        $categories = commercesCategories::where('commerce_type', $commerce->commerce_type_vp)->where('state', 1)->get();
        return view('commerces.categories', compact('categories', 'commerce'));
    }
    public function categoriesStore(Request $request)
    {
        $request->validate([
            'category_id' => 'exists:commerce_categories,id',
            'commerce' => 'exists:commerces,id'
        ]);
        $commerce = Commerce::find($request->commerce);
        $commerce->getCategories()->attach($request->category_id);
        return back();
    }
    public function categoriesDelete(Request $request)
    {

        $request->validate([
            'category_id' => 'exists:commerce_categories,id',
            'commerce' => 'exists:commerces,id'
        ]);

        $commerce = Commerce::find($request->commerce);
        if ($commerce->getCategories()->detach($request->category_id)) {
            # code...
            return response()->json(['code' => 200, 'data' => $commerce], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
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
        // dd($request->all());
        $request->merge(['id' => $id]);
        $store = $this->funUpdate($request)->getData();

        if ($store->code == 200) {
            return back()->with('success', 'Actualizado con exito');
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
        $this->funDelete(request());
    }

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = Commerce::where('state', '<>', 2)->get();
        } else {
            $list = Commerce::where('id', $id)->where('state', '<>', 2)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByType(Request $request)
    {
        $request->validate([
            'tipo' => 'required'
        ]);

        $tipo = $request->tipo;
        if ($tipo == 0) {
            $list = Commerce::where('state', 1)->with('getUser')->get();
        } else {
            $list = Commerce::where('commerce_type_vp', $tipo)->with('getUser')->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:commerces,id'
        ]);
        $element = Commerce::where('id', $request->id)->with(['getUser.getCommerceAddress'])->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'required|email|unique:users,email',
            'cellphone' => 'required|digits:10|unique:users,cellphone',
            'password' => 'required|min:6|max:500|confirmed',
            'img_profile' => 'image|mimes:jpg,jpeg,png',
            'bussiness_name' => 'required|string',
            'nit' => 'required|numeric|unique:commerces,nit',
            'nit' => 'unique:users,document',
            'delivery_config' => 'required|numeric',
            'commerce_type_vp' => 'required|numeric',
            'categories' => 'array',
            'is_opened' => 'required',
            'lng' => 'required',
            'lat'=>'required',
            'commerce_address' => 'required'
        ]);
        // dd($request->all());

        $usuario = new User();
        /*Asigno atributos que no pueden ser nulo  para tipo comercion y user */
        $usuario->document = $request->nit;
        $usuario->document_type_vp = 2;
        $usuario->name = $request->name;
        $usuario->last_name = $request->last_name;
        if ($request->img_profile) {
            $pictureName = $request->img_profile->store('product_images', 'public');
            $usuario->photo = $pictureName;
        }
        $usuario->email = $request->email;
        $usuario->cellphone = $request->cellphone;
        $usuario->password = bcrypt($request->password);
        $usuario->rol_id = 2;
        $usuario->user_state = 0;
        $send = send_sms($request->cellphone, 'Hola, bienvenid@ a Favores. Su proceso de creación de comercio está en solicitud, será notificado en caso de ser aceptado o denegado. Puede revisar su correo electrónico para más información, en caso de no visualizar ningun mensaje, aconsejamos revisar su carpeta de spam.');

        $commerce = new Commerce();
        $commerce->bussiness_name = $request->bussiness_name;
        $commerce->nit = $request->nit;
        $commerce->commerce_type_vp = $request->commerce_type_vp;
        $commerce->delivery_config = $request->delivery_config;
        $commerce->state = 3;
        $msj = [
            'nombre' => $request->name,
            'comercio'=> $request->bussiness_name,
            'nit' => $request->nit,
            'correo' => $request->email,
            'cellphone' => $request->cellphone
        ];
        Mail::to($request->email)->send(new CreatedCommerce($msj));

        try {
            DB::transaction(function () use ($usuario, $commerce, $request) {
                $usuario->save();
                $commerce->user_id = $usuario->id;
                $commerce->save();

                $addresses = $commerce->getUser->getAddresses;
                if ($addresses->count() > 0) {
                    $address = $addresses->first();
                    $address->address = $request->commerce_address;
                    $address->lat = $request->lat;
                    $address->lng = $request->lng;
                    $address->update();
                } else {
                    $address = new UserAddress();
                    $address->user_id = $commerce->getUser->id;
                    $address->name = $commerce->bussiness_name;
                    $address->address = $request->commerce_address;
                    $address->lat = $request->lat;
                    $address->lng = $request->lng;
                    $address->save();
                }

                if (isset($request->categories)) {
                    $commerce->getCategories()->sync($request->categories);
                }
            });
            return response()->json(['code' => 200, 'data' => $commerce], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        // dd($request->all());
        $commerce = Commerce::find($request->id);
        $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'required|email',
            'cellphone' => 'required|digits:10|unique:users,cellphone,' . $commerce->getUser->id,
            'bussiness_name' => 'required|string',
            'nit' => 'required|numeric',
            'delivery_config' => 'required',
            'categories' => 'required|array',
            'delivery_value' => 'nullable|numeric',
            'password' => 'nullable|min:6',
            'is_opened' => 'required'
        ]);

        $data = $request->input();
        if ($request->user_state == 0) {
            $commerce->getUser->user_state = 0;
            $commerce->getUser->update();
        }
        if($request->state == 1){
            $commerce->getUser->user_state = 1;
            $commerce->getUser->update();
        }
        if ($request->file('profile_img')) {
            Storage::disk("public")->delete($commerce->getUser->photo);
            $imgName = $request->profile_img->store('user_images', 'public');
            $commerce->getUser->photo = $imgName;
            $commerce->getUser->update();
        }

        $commerce->getUser->name = $request->name;
        $commerce->getUser->last_name = $request->last_name;
        $commerce->getUser->email = $request->email;
        $commerce->getUser->cellphone = $request->cellphone;
        if (!is_null($request->password)) {
            $commerce->getUser->password = bcrypt($request->password);
            $send = send_sms($request->cellphone, 'Su contraseña ha sido generada con éxito!\nContraseña : '.$request->password);
        }

        $addresses = $commerce->getUser->getAddresses;

        if ($addresses->count() > 0) {
            $address = $addresses->first();
            $address->address = $request->commerce_address;
            $address->lat = $request->lat;
            $address->lng = $request->lng;
            $address->update();
        } else {
            $address = new UserAddress();
            $address->user_id = $commerce->getUser->id;
            $address->name = $commerce->bussiness_name;
            $address->address = $request->commerce_address;
            $address->lat = $request->lat;
            $address->lng = $request->lng;
            $address->save();
        }

        $commerce->getUser->update();

        $commerce->getCategories()->sync($request->categories);

        if ($commerce->update($data)) {
            return response()->json(['code' => 200, 'data' => $commerce], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funDelete(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:commerces,id'
        ]);

        $model = Commerce::where('id', $request->id)->first();
        $model->state = 2;
        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
    public function activateCommerce(Request $request){

        $commerce = Commerce::where('id', $request->idCommerceToActivate)->first();
        $user = User::where('id',$commerce->user_id)->first();
        $user->user_state = 1;
        $user->update();
        $commerce->state = 1;
        $msj = [
            'nombre' => $user->name,
            'comercio'=> $commerce->bussiness_name,
            'correo' => $user->email,
            'cellphone' => $user->cellphone
        ];
        Mail::to($user->email)->send(new ConfirmationCommerce($msj));
        if ($commerce->update()) {
            return back()->with('success', 'Comercio activado con éxito!');
        } else {
           return back()->with('success', 'Error al activar');
        }
    }
}
