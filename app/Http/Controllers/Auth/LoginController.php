<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'cellphone' => 'required|exists:users,cellphone',
            'password' => 'required'
        ]);
        if (Auth::attempt(['cellphone' => $request->cellphone, 'password' => $request->password, 'user_state' => 1])) {
            return redirect()->route('administrator.home');
        } else {
            return back()->with('failed', 'Usuario o contraseña incorrectos. Si el problema persiste contacte al adminstrador.');
        }
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('administrator.home');
        } else {
            return view('auth.login');
        }
    }

    public function showSendCodeForm()
    {
        return view('auth.sendCode');
    }

    public function showConfirmCodeForm()
    {
        return view('auth.confirmCode');
    }

    public function showChangePasswordForm()
    {
        return view('auth.changePassword');
    }

    public function confirmCode(Request $request)
    {
        $request->validate([
            'cellphone' => 'required|exists:users,cellphone'
        ]);
        $cellphone = $request->cellphone;

        $user = User::where('cellphone', $cellphone)->first();
        $codigo = Str::random(7);
        $response = send_sms($cellphone, 'Su código de confirmacion es ' . $codigo);
        // dd($response);
        $user->code = $codigo;
        $user->update();

        $request->session()->put('user_cellphone', $request->cellphone);

        return redirect()->route('auth.confirmCode');
    }

    public function validateConfirmCode(Request $request)
    {
        $request->merge([
            'cellphone' => session('user_cellphone'),
        ]);
        $request->validate([
            'cellphone' => 'required|exists:users,cellphone',
            'code' => 'required|exists:users,code'
        ]);
        return redirect()->route('auth.changePassword');
    }

    public function updatePassword(Request $request)
    {
        $request->merge(['cellphone' => session('user_cellphone')]);
        $request->validate([
            'cellphone' => 'required|exists:users,cellphone',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::where('cellphone', $request->cellphone)->first();

        $user->password = bcrypt($request->password);
        $user->update();
        $request->session()->forget('user_cellphone');
        return redirect('/')->with('success', 'Constraseña reestablecida correctamente');
    }
}