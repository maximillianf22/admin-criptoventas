<?php

namespace App\Http\Controllers\Administrator;

use App\Customer;
use App\Distributor;
use App\Http\Controllers\Controller;
use App\ParameterValue;
use App\Rol;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\codeConfirmed;


class CustommerController extends Controller
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

        $customers = $this->funGetList(request())->original['data'];
        if (!is_null(request()->document)) {
            $customers = $customers->filter(function ($customer) {
                return $customer->getUser->document == request()->document;
            });
        }
        if (!is_null(request()->fullname)) {
            $customers = $customers->filter(function ($customer) {
                return false !== stripos($customer->getUser->name . " " . $customer->getUser->last_name, request()->fullname);
            });
        }
        if (!is_null(request()->cellphone)) {
            $customers = $customers->filter(function ($customer) {
                return $customer->getUser->cellphone == request()->cellphone;
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $customers = $customers->where('state', request()->state);
        }
        $data = array(
            'customers' => $customers
        );
        return view('customers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ParameterValue::where('parameter_id', 1)->get();
        $distributors = Distributor::where('state', 1)->get();

        $data = array(
            'types' => $types,
            'distributors' => $distributors
        );
        return view('customers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = $this->funCreate($request)->getData()->code;

        if ($status == 200) {
            return redirect()->route('custommers.index')->with('success', 'Cliente creado satisfactoriamente');
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
        $code = $this->funShow(request())->original['code'];
        $data = $this->funShow(request())->original['data'];
        $types = ParameterValue::where('parameter_id', 1)->get();
        $profile = Rol::whereIn('name', ['Clientes', 'Mayoristas'])->where('state', 1)->get();
        $distributors = Distributor::where('state', 1)->get();
        $data = array(
            'data' => $data,
            'types' => $types,
            'profile' => $profile,
            'distributors' => $distributors
        );
        if ($code == 200) {
            //dd($data);
            return view('customers.edit', $data);
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
        $request->merge(['id' => $id]);
        $edit = $this->funUpdate($request);
        if ($edit->original['code'] == 200) {
            return back()->with('success', 'Cliente editado');
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
        return $this->funDelete(request());
    }



    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = Customer::where('state', 1)->with('getUser');
        } else {
            $list = Customer::where('state', 1)
                ->with('getUser')
                ->whereHas('getOrders', function ($p) use ($id) {
                    $p->where('commerce_id', $id);
                });
            // dd($list->get());
        }
        return response()->json(['code' => 200, 'data' => $list->get()], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id'
        ]);

        $element = Customer::where('id', $request->id)->with('getUser')->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }
    public function uniqueString($name)
    {
        $codigo = $name . rand(1, 5000);
        while (!empty(Customer::where('distributor_code', $codigo)->first())) {
            $codigo = $name . rand(1, 5000);;
        }
        return $codigo;
    }

    public function funCreate(Request $request)
    {

        $request->validate([
            /*  'document' => 'required|unique:users,document|string|min:6|max:500',
            'document_type_vp' => 'required|numeric', */
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10|unique:users,cellphone',
            'password' => 'required|max:500|confirmed',
            'distributor_id' => 'exclude_if:distributor_id,0|exists:distributors,id',
            'profile_id' => 'required|exists:roles,id'
        ]);

        $result = DB::transaction(function () use ($request) {
            try {
                $user = new User();
                // $user->document = $request->document;
                // $user->document_type_vp = $request->document_type_vp;
                $user->name = $request->name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->cellphone = $request->cellphone;
                $user->password = bcrypt($request->password);
                $user->rol_id = $request->profile_id;
                if (is_null($request->user_state)) {
                    $user->user_state = 1;
                } else {
                    $user->user_state = $request->user_state;
                }
                $user->code_confirmed = 0;
                $user->code = rand(0, 999999);
                $user->save();

                $customer = new Customer();
                $customer->user_id = $user->id;
                if ($request->distributor_id == "0") {
                    $customer->distributor_id = null;
                } else {
                    $customer->distributor_id = $request->distributor_id;
                }
                $customer->save();

                return response()->json(['code' => 200, 'data' => $customer->load('getUser')], 200);
            } catch (\Exception $e) {

                return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear el cliente' . $e->getMessage()], 530);
            }
        });
        return $result;
    }

    public function funUpdate(Request $request)
    {
        $customer = Customer::find($request->id);

        $request->validate([
            // 'document' => 'required|string|min:6|max:500|unique:users,document,' . $customer->getUser->id,
            // 'document_type_vp' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10|unique:users,cellphone,' . $customer->getUser->id,
            'password' => 'nullable',
            'distributor_id' => 'exclude_if:distributor_id,0|numeric|exists:distributors,id',
            'user_state' => 'required'
        ]);
        if (!is_null($request->profile_id)) {
            $request->validate([
                'profile_id' => 'required|exists:roles,id'
            ]);
        }

        $data = $request->input();
        $transRes = DB::transaction(function () use ($request, $customer) {

            // $customer->getUser->document = $request->document;
            // $customer->getUser->document_type_vp = $request->document_type_vp;
            $customer->getUser->name = $request->name;
            $customer->getUser->last_name = $request->last_name;
            $customer->getUser->email = $request->email;
            $customer->getUser->cellphone = $request->cellphone;
            $customer->getUser->user_state = $request->user_state;
          
            if($customer->getUser->rol_id != 4){
                if($request->profile_id == 4){
                    $distributorexists = Distributor::where('user_id', $customer->getUser->id)->first();
                    if(empty($distributorexists)){
                        $distributor = new Distributor();
                        $distributor->user_id = $customer->getUser->id;
                        $distributor->distributor_code = strtoupper($customer->getUser->name . $customer->getUser->id);
                        $distributor->distributor_percent = 0;
                        $distributor->save();

                        if($customer->getUser->rol_id == 3){
                            $customerChange = Customer::where('user_id', $customer->getUser->id)->first();
                            $customerChange->state = 2;
                            $customerChange->update();
                        }
                    }else{
                        if($customer->getUser->rol_id == 3){
                            $customerChange = Customer::where('user_id', $customer->getUser->id)->first();
                            $customerChange->state = 2;
                            $customerChange->update();
                        }
                        $distributorexists->state = 1;
                        $distributorexists->update();
                    }
                }else{
                    $customer->getUser->rol_id = $request->profile_id;
                }
            }else{
                if($request->profile_id == 3){
                    $distributorexists = Distributor::where('user_id', $customer->getUser->id)->first();
                    $distributorexists->state = 2;
                    $distributorexists->update();
                    $customerChange = Customer::where('user_id', $customer->getUser->id)->first();
                    $customerChange->state = 1;
                    $customerChange->update();
                }
                
            }
            $customer->getUser->rol_id = $request->profile_id;
            if (!is_null($request->password)) {
                $customer->getUser->password = bcrypt($request->password);
            }
            $customer->getUser->update();
            if ($request->distributor_id == "0") {
                $customer->distributor_id = null;
            } else {
                $customer->distributor_id = $request->distributor_id;
            }
            $customer->update();
            return response()->json(['code' => 200, 'data' => $customer], 200);
        });

        if ($transRes->getData()->code == 200) {
            return $transRes;
        }
        return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id'
        ]);

        $customer = Customer::find($request->id);
        $customer->state = 2;
        if ($customer->update()) {
            return response()->json(['code' => 200, 'data' => $customer], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }

    public function funShowByCellphone(Request $request)
    {
        $request->validate([
            'cellphone' => 'required|exists:users,cellphone'
        ]);

        $user = User::where('cellphone', $request->cellphone)->first();
        $customer = Customer::where('user_id', $user->id)->where('state', 1)->with('getUser')->first();
        $userData = $customer;
        if (empty($customer)) {
            $distributor = Distributor::where('user_id', $user->id)->where('state', 1)->with('getUser')->first();
            $userData = $distributor;
            if (empty($distributor)) {
                return response()->json(['code' => 400, 'data' => $customer, 'message' => 'Usuario no es un cliente'], 400);
            }
        }
        $token =  $user->createToken('MyApp')->accessToken;
        $userData['token'] = $token;
        $userData['confirmed'] = $user->code_confirmed;

        return response()->json(['code' => 200, 'data' => $userData], 200);
    }

    public function funSendCode(Request $request)
    {
        try {
            $request->validate([
                'cellphone' => 'required|exists:users,cellphone'
            ]);

            $randomCode = rand(100000, 999999);
            $user = User::where('cellphone', $request->cellphone)->first();
            $user->code = $randomCode;
            $user->code_confirmed = 0;
            $email = $user->email;
            $user->update();

            $data = ['email' => $email, 'name' => $user->name, 'code' => $randomCode];
            Mail::send('mails.notificacion', $data, function ($message) use ($data) {
                $message->from('dgse.informatica@gmail.com', 'Criptoventas');
                $message->to( $data['email'], $data['name']);
                $message->subject('Codigo de confirmacion '.$data['code']);
            });

            //send_sms($user->cellphone, 'Su código de verificación es: ' . $randomCode, 1);
            

           /* $msj = [
                'nombre' => $user->name,
                'code' => $randomCode
            ];*/

           //Mail::to($email)->send(new codeConfirmed($msj));
            
            return response()->json(['code' => 200, 'data' => $randomCode], 200);
        } catch (\Throwable $th) {
            return response()->json(['code' => 200, 'data' => null], 200);
        }
    }
    public function activateCodeConfirm(Request $request){
        $user = User::where('id', $request->idCustomerToActivate)->first();
        $user->code_confirmed = 1;
        $user->user_state = 1;
        if ($user->update()) {
            return back()->with('success', 'Usuario activado con éxito!');
        } else {
           return back()->with('success', 'Error al activar');
        }
    }
}
