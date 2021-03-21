<?php

namespace App\Http\Controllers\Administrator;

use App\Distributor;
use App\DistributorComissions;
use App\Http\Controllers\Controller;
use App\Order;
use App\Customer;
use App\ParameterValue;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DistributorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distributors = $this->funGetList(request())->original['data'];
        if (!is_null(request()->document)) {
            $distributors = $distributors->filter(function ($distributor) {
                return $distributor->getUser->document == request()->document;
            });
        }
        if (!is_null(request()->fullname)) {
            $distributors = $distributors->filter(function ($distributor) {
                return false !== stripos($distributor->getUser->name . " " . $distributor->getUser->last_name, request()->fullname);
            });
        }
        if (!is_null(request()->cellphone)) {
            $distributors = $distributors->filter(function ($distributor) {
                return $distributor->getUser->cellphone == request()->cellphone;
            });
        }
        if (!is_null(request()->state) && request()->state != -1) {
            $distributors = $distributors->where('state', request()->state);
        }
        return view('distributors.index', ['distributors' => $distributors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = ParameterValue::where('parameter_id', 1)->get();
        return view('distributors.create', ['types' => $types]);
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
            return redirect()->route('distributors.index');
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
        request()->merge(['id' => $id]);
        $code = $this->funShow(request())->original['code'];
        $data = $this->funShow(request())->original['data'];

        $comissions = DistributorComissions::where('distributor_id', $id)->get();
        $total = 0;
        if(!is_null($comissions)){
            foreach ($comissions as $venta) {
                if(!is_null($venta->getOrder)){   
                    $total += $venta->getOrder->sub_total * $venta->distributor_percent / 100;
                }
            }
        }

        return view('distributors.ventas', ['total' => $total, 'distributor' => $data, 'comissions' => $comissions]);
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
        $data = array(
            'data' => $data,
            'types' => $types
        );
        if ($code == 200) {
            return view('distributors.edit', $data);
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
            return back()->with('success', 'Distribuidor actualizado correctamente');
        } else {
            return back()->with('failed', 'Ocurrió un error al actualizar. Intente mas tarde');
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

    /****** FUNCIONES ******/

    public function funGetList(Request $request)
    {
        $list = Distributor::where('state', 1)->with('getUser')->get();
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:distributors,id'
        ]);

        $element = Distributor::where('id', $request->id)->with('getUser')->first();
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
            // 'document' => 'required|string|min:6|max:500|unique:users,document',
            // 'document_type_vp' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10|unique:users,cellphone',
            'password' => 'required|max:500',
        ]);

        $user = new User();
        $distributor = new Distributor();

        try {
            DB::transaction(function () use ($request, $user, $distributor) {
                $user->document = $request->document;
                $user->document_type_vp = $request->document_type_vp;
                $user->name = $request->name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->cellphone = $request->cellphone;
                $user->password = bcrypt($request->password);
                $user->code = rand(100000, 999999);
                $user->rol_id = 4;
                $user->user_state = 1;
                $user->code_confirmed = 0;
                $user->save();

                $distributor->user_id = $user->id;
                $distributor->distributor_code = strtoupper($user->name . $user->id);
                $distributor->distributor_percent = 0;
                $distributor->save();
            });
            send_sms($user->cellphone, 'Su código de distribuidor es: ' . $distributor->distributor_code);
            return response()->json(['code' => 200, 'data' => $distributor->load('getUser')], 200);
        } catch (\Exception $e) {
            // dd($e);
            // return $e;
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear producto de supermercado'], 530);
        }
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            // 'document' => 'required|string|min:6|max:500',
            // 'document_type_vp' => 'required|numeric',
            'name' => 'required|string',
            'last_name' => 'required|max:500',
            'email' => 'nullable|email',
            'cellphone' => 'required|digits:10',
            'password' => 'nullable|min:6',
            'distributor_code' => 'required',
            'distributor_percent' => 'required',
            'state' => 'required'
        ]);
        $data = $request->input();
        $distributor = Distributor::find($request->id);
        try {
            DB::transaction(function () use ($request, $distributor) {
                $distributor->getUser->document = $request->document;
                $distributor->getUser->document_type_vp = $request->document_type_vp;
                $distributor->getUser->name = $request->name;
                $distributor->getUser->last_name = $request->last_name;
                $distributor->getUser->email = $request->email;
                $distributor->getUser->cellphone = $request->cellphone;
                $distributor->getUser->user_state = $request->state;

                if($request->profile_id == 3){
                    $customerexists = Customer::where('user_id', $distributor->getUser->id)->first();
                    if(empty($customerexists)){
                        $newCustomer = new Customer();
                        $newCustomer->user_id = $distributor->getUser->id;
                        $newCustomer->save();

                        $distributorChange = Distributor::where('user_id', $distributor->getUser->id)->first();
                        $distributorChange->state = 2;
                        $distributorChange->update();

                        Customer::where('distributor_id', $request->id)->update(['distributor_id' => 0]);
                     
                    }else{
                        $customerexists->state = 1;
                        $customerexists->update();
                        $distributorChange = Distributor::where('user_id', $distributor->getUser->id)->first();
                        $distributorChange->state = 2;
                        $distributorChange->update();
                        Customer::where('distributor_id', $request->id)->update(['distributor_id' => null]);
                    }
                    
                }else{
                    $distributorChange = Distributor::where('user_id', $distributor->getUser->id)->first();
                    $distributorChange->state = 1;
                    $distributorChange->update();
                    if($distributor->getUser->rol_id == 3){
                        $customerexists = Customer::where('user_id', $distributor->getUser->id)->first();
                        $customerexists->state = 2;
                        $customerexists->update();
                    }
                }
                $distributor->getUser->rol_id = $request->profile_id;

                if (!is_null($request->password)) {
                    $distributor->getUser->password = bcrypt($request->password);
                }
                $distributor->getUser->update();

                $distributor->distributor_code = $request->distributor_code;
                $distributor->distributor_percent = $request->distributor_percent;
                $distributor->update();
            });
            return response()->json(['code' => 200, 'data' => $distributor], 200);
        } catch (\Throwable $th) {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id'
        ]);

        $distributor = Distributor::find($request->id);
        $distributor->state = 2;
        if ($distributor->update()) {
            return response()->json(['code' => 200, 'data' => $distributor], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
}