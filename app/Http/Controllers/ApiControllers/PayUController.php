<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PayUController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $order = Order::find($id);
        if ($order->payment_state == 21 || $order->payment_state == 19) {

            $rand = Str::random(20);
            $apiKey = env('PAYU_APIKEY');
            $merchanId = env('PAYU_MERCHANTID');
            $amount = $order->total;
            $reference = $order->reference . $rand;
            $signature = "$apiKey~$merchanId~$reference~$amount~COP";
            $signature = md5($signature);
            return view('payU.postForm', ['signature' => $signature, 'order' => $order, 'reference' => $reference]);
        } else {
            response()->json(['message' => "orden con referencia $order->reference ya fue pagada"], 400);
        }
    }
    public function responsePayU(Request $request)
    {
        if ($request->has('extra1')) {
            $state = $request->transactionState ?? -1;
            $order = Order::find($request->extra1);
            return view('payU.postForm', ['state' => $state, 'order' => $order]);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
