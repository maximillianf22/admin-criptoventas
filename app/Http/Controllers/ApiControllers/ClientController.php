<?php

namespace App\Http\Controllers\ApiControllers;

use App\Distributor;
use App\Http\Controllers\Administrator\CustommerController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends CustommerController
{

    public function makeLogin(Request $request)
    {
        $loginValidation = Validator::make($request->all(), [
            'cellphone' => 'required|string|exists:users,cellphone',
            'password' => 'required|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'grant_type' => 'required|string',
        ]);

        if ($loginValidation->fails()) {
            return response()->json([
                'code' => 400, 'data' => null,
                'message' => $loginValidation->errors()->first()
            ], 400);
        }

        $client = $request->input('client_id');
        $secret = $request->input('client_secret');
        $grant = $request->input('grant_type') == 'password' ? 1 : 0;
        $email = $request->input('cellphone');
        $password = $request->input('password');
        $tokenFirebase = $request->input('tokenNotification');

        $validateRequest = DB::table('oauth_clients')->where([
            ['id', $client],
            ['secret', $secret],
            ['password_client', $grant]
        ])->first();

        if (!is_null($validateRequest)) {
            $response = $this->funShowByCellphone($request)->getData();

            if ($response->code == 200) {
                $userData = $response->data;

                //return response()->json(['code' => 200, 'data' => $userData], 200);

                //Valido si tiene id del usuario lo que indicaria que es un cliente
                if (
                    empty($userData->user_id) ||
                    $userData->get_user == null ||
                    $userData->get_user->state != 1 ||
                    $userData->get_user->user_state != 1
                ) {
                    return response()->json(['code' => 430, 'data' => null, 'message' => 'El usuario es invalido'], 430);
                }

                //Consulto el usuario para obtener, las contraseñas
                $customerId = $userData->user_id;
                $user = User::where('id', $customerId)->first();
                if ($user->state == 0) {
                    return response()->json(['code' => 423, 'data' => null, 'message' => 'Usuario inabilitado'], 420);
                }
                $distributor = Distributor::find($userData->distributor_id ?? 0);
                $userData->dist = $distributor;

                if (Hash::check($password, $user->password)) {
                    $user->token_firebase = $tokenFirebase;
                    if($user->update()) {
                        return response()->json(['code' => 200, 'data' => $userData], 200);
                    }else{
                        return response()->json(['code' => 401, 'data' => null, 'message' => 'Error en la autenticacion'], 401);
                    }
                    
                } else {
                    return response()->json(['code' => 420, 'data' => null, 'message' => 'la contraseña es incorrecta'], 420);
                }
            } else if (!is_null($response->code)) {
                return response()->json(['code' => 500, 'data' => null, 'message' => $response->message], 500);
            }
            return response()->json(['code' => 500, 'data' => null, 'message' => 'el code es null'], 500);
        }

        return response()->json(['code' => 401, 'data' => null, 'message' => 'Error en la autenticacion'], 401);
    }
}
