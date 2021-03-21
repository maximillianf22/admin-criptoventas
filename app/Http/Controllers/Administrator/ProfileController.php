<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\ParameterValue;
use App\Rol;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){
        $log_user = Auth::user();
        request()->merge(['id' => $log_user->id]);
        $types = ParameterValue::where('parameter_id', 1)->get();
        $roles = Rol::where('unique', 1)->whereNotIn('id', [2])->where('state', 1)->get();
        $user = $this->funGetList(request())->original['data'];

        if (!is_null(request()->document)) {
            $user = $user->where('document', request()->document);
        }
        if (!is_null(request()->cellphone)) {
            $user = $user->where('cellphone', request()->cellphone);
        }
        if (!is_null(request()->rol_id) && request()->rol_id != -1) {
            $user = $user->where('rol_id', request()->rol_id);
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $user = $user->where('state', request()->state);
        }
        $data = array(
            'user' => $user,
            'types' => $types,
            'roles' => $roles
        );

        return view('profile.index', $data);
    }

    public function update(Request $request)
    {
        $update = $this->funUpdate($request);
        if ($update->original['code'] == 200) {
            session()->flash('success', 'El usuario se actualizo correctamente');
            return redirect()->route('profile.index');
        } elseif($update->original['code'] == 530){
            session()->flash('wrong', 'Hubo un problema. El usuario no se pudo actualizar');
        } else {
            session()->flash('password', 'Las contraseñas no concuerdan');
            return back();
        }
    }

    /* Funciones */

    public function funGetList(Request $request)
    {
        $list = User::find($request->id);
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'document' => 'required|string|min:6|max:500',
            'document_type_vp' => 'required|numeric',
            'profileImg' => 'image|mimes:jpg,jpeg,png|max:499',
            'name' => 'required|string|max:500',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10',
            'password' => 'nullable|min:6',
            'password2' => 'nullable|min:6',
        ]);

        $user = User::find($request->id);
        $user->document = $request->document;
        $user->document_type_vp = $request->document_type_vp;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->cellphone = $request->cellphone;
        if (!is_null($request->password) && !is_null($request->password2)) {
            if($request->password == $request->password2){
                $user->password = bcrypt($request->password);
            }else{
                return response()->json(['code' => 540, 'data' => null, 'message' => 'Error al crear'], 540);
            }
        }
        if ($request->profileImg) {
            $user->photo = $request->profileImg->store('user_images', 'public');
        }
        if ($user->update()) {
            return response()->json(['code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }


    public function notificationApp(Request $request) {

        $cellphone = $request->cellphone;
        $message = $request->message;
        $type = $request->type;

        $user = User::where('cellphone', $cellphone)->first();
        if(!empty($user)) {
            if($user->token_firebase <> null) {

                $title = $request->type == 1 ? "Cambio de estado" : "Mensaje Nuevo";

                $dataNotification = [
                    "title"     => "Favores.co - ".$title,
                    "message"   => $message,
                    "type"      => $type //1: Cambio de pedido : 2:Chat
                ];
                $token = $user->token_firebase;
    
                $this->sendNotication($dataNotification, $token);

            }
        }

    }

}
