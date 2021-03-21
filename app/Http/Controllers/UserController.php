<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use App\ParameterValue;
use App\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        request()->merge(['id' => 0]);
        $users = $this->funGetList(request())->original['data'];
        $roles = Rol::where('unique', 1)->whereNotIn('id', [2])->where('state', 1)->get();

        if (!is_null(request()->document)) {
            $users = $users->where('document', request()->document);
        }
        if (!is_null(request()->cellphone)) {
            $users = $users->where('cellphone', request()->cellphone);
        }
        if (!is_null(request()->rol_id) && request()->rol_id != -1) {
            $users = $users->where('rol_id', request()->rol_id);
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $users = $users->where('state', request()->state);
        }
        $data = array(
            'users' => $users,
            'roles' => $roles
        );

        return view('users.index', $data);
    }

    public function show()
    {
    }

    public function create()
    {
        $types = ParameterValue::where('parameter_id', 1)->get();
        $rol = Rol::where('unique', 1)->whereNotIn('id', [2])->where('state', 1)->get();
        $data = array(
            'types' => $types,
            'roles' => $rol
        );
        return view('users.create', $data);
    }

    public function store(Request $request)
    {
        $store = $this->funCreate($request);
        if ($store->original['code'] == 200) {
            return redirect()->route('users.index');
        } else {
            return back();
        }
    }

    public function update(Request $request)
    {
        $update = $this->funUpdate($request);
        if ($update->original['code'] == 200) {
            return redirect()->route('users.index');
        } else {
            return back();
        }
    }

    public function edit($id)
    {
        request()->merge(['id' => $id]);
        $user = $this->funShow(request())->original['data'];
        $types = ParameterValue::where('parameter_id', 1)->get();
        $roles = Rol::where('unique', 1)->whereNotIn('id', [2])->where('state', 1)->get();

        $data = array(
            'user' => $user,
            'types' => $types,
            'roles' => $roles
        );
        return view('users.edit', $data);
    }

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
            $list = User::where('state', '<>', 2)->whereHas('getRol', function ($q) {
                $q->where('unique', 1)->whereNotIn('id', [2]);
            })->get();
        } else {
            $list = User::where('id', $id)
                ->where('state', '<>', 2)
                ->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $user = User::where('id', $request->id)->with(['getAddresses' => function ($q) {
            $q->where('state', '<>', 2);
        }])->first();

        return response()->json(['code' => 200, 'data' => $user], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'document' => 'required|string|min:6|max:500',
            'document_type_vp' => 'required|numeric',
            'profileImg' => 'image|mimes:jpg,jpeg,png|max:499',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10',
            'rol_id' => 'required|exists:roles,id',
            'password' => 'required|max:500',
            'code' => 'nullable|string|max:500',
        ]);
        $data = $request->input();
        $usuario = new User($data);
        if ($request->profileImg) {
            $pictureName = date('Y_m_d_H_i_s') . "_" . $request->document . "." . $request->profileImg->getClientOriginalExtension();
            $request->profileImg->storeAs('user_images/', $pictureName, 'public');
            $usuario->photo = $pictureName;
        }
        $usuario->rol_id = $request->rol_id;
        $usuario->password = bcrypt($request->password);
        $usuario->user_state = 1;
        if ($usuario->save()) {
            return response()->json(['code' => 200, 'data' => $usuario], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'document' => 'required|string|min:6|max:500',
            'document_type_vp' => 'required|numeric',
            'profileImg' => 'image|mimes:jpg,jpeg,png|max:499',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10',
            'password' => 'nullable|min:6',
            'rol_id' => 'required|numeric',
            'user_state' => 'required|numeric|max:11',
        ]);

        $user = User::find($request->id);
        $user->document = $request->document;
        $user->document_type_vp = $request->document_type_vp;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->cellphone = $request->cellphone;
        if (!is_null($user->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->rol_id = $request->rol_id;
        $user->user_state = $request->user_state;
        if ($request->profileImg) {
            $pictureName = date('Y_m_d_H_i_s') . "_" . $request->document . "." . $request->profileImg->getClientOriginalExtension();
            $request->profileImg->storeAs('user_images/', $pictureName, 'public');
            $user->photo = $pictureName;
        }
        if ($user->update()) {
            return response()->json(['code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $model = User::where('id', $request->id)->first();
        $model->user_state = 0;
        $model->state = 2;
        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }


    public function funShowByCellphone(Request $request)
    {
        $request->validate([
            'cellphone' => 'required|exists:users,cellphone'
        ]);

        $user = User::where('cellphone', $request->cellphone)->with(['getAddresses' => function ($q) {
            $q->where('state', '<>', 2);
        }])->first();

        $token =  $user->createToken('MyApp')->accessToken;
        $user['token'] = $token;

        return response()->json(['code' => 200, 'data' => $user], 200);
    }

    public function funGeneratePassword()
    {
        $password = Str::random(12);
        return response()->json(['code' => 200, 'data' => $password], 200);
    }
}
