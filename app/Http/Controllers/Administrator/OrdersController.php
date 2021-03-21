<?php

namespace App\Http\Controllers\Administrator;

use App\cupones;
use App\Customer;
use App\DistributorComission;
use App\Distributor;
use App\DistributorComissions;
use App\Http\Controllers\ApiControllers\CouponsController;
use App\Http\Controllers\Controller;
use App\Ingredient;
use App\Order;
use App\RangeHour;
use App\PriceList;
use App\OrderDetail;
use App\ParameterValue;
use App\Product;
use App\ProductCategory;
use App\RestaurantProduct;
use App\User;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use DateTime;

class OrdersController extends Controller
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

        $data = $this->funGetList(request())->original;
        $state = ParameterValue::Where('parameter_id', 6)->get();
        $state2 = ParameterValue::where('parameter_id', 7)->get();
        if ($data['code'] == 200) {
            $orders = $data['data'];
            return view('orders.list', ['orders' => $data['data'], 'state' => $state, 'state2' => $state2]);
        }
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
    public function show($id)
    {
        request()->merge(['id' => $id]);
        $data = $this->funShow(request())->original;
        $vp = ParameterValue::where('id', $data['data']->payment_type_vp)->first();
        $actualState = $data['data']->getOrderState;
        $nextState = ParameterValue::where('parameter_id', 6)->where('extra', $actualState->extra + 1)->first();
        if ($data['code'] == 200) {
            return view('orders.detail', ['data' => $data['data'], 'vp' => $vp, 'nextState' => $nextState]);
        }
    }
    public function payUConfirm(Request $request)
    {
        $order = Order::find($request->extra1);
        $state = $request->state_pol == 4 ? 20 : 21;
        $order->payment_state = $state;
        $order->save();
    }

    public function factura($id)
    {
        request()->merge(['id' => $id]);
        $data = $this->funShow(request())->original;
        $vp = ParameterValue::where('id', $data['data']->payment_type_vp)->first();
        $actualState = $data['data']->getOrderState;
        $nextState = ParameterValue::where('parameter_id', 6)->where('extra', $actualState->extra + 1)->first();

        if ($data['code'] == 200) {
            /*  return  View('orders.facturas.factura', ['data' => $data['data'], 'vp' => $vp, 'nextState' => $nextState]); */
            $pdf = PDF::loadView('orders.facturas.factura', ['data' => $data['data'], 'vp' => $vp, 'nextState' => $nextState]);
            return $pdf->download('factura.pdf');
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
        $request->merge(['id' => $id]);
        $data = $this->funUpdate($request)->original;
        if ($data['code'] == 200) {
            return redirect()->route('order.index');
        }
    }

    public function updateState(Request $request)
    {
        $update = $this->funUpdateState($request);
        return $update;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
    public function canceleOrder(Request $request)
    {
        $order = Order::find($request->id);
        $order->order_state = 23;
        $order->save();
        return redirect()->route('orders.show', ['order' => $request->id]);
    }

    /********** FUNCIONES ***********/

    public function funGetList(Request $request)
    {
        $id = $request->id;
        if ($id == 0) {
            $list = Order::where('state', 1);
            if ($request->has('reference') && !empty($request->reference))
                $list->where('reference', 'like', '%' . $request->reference . '%');

            if ($request->has('customer') && !empty($request->customer))
                $list->whereHas('getCustomer.getUser', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->customer . '%');
                });
            if ($request->has('init_date') && !empty($request->init_date)) {

                $list->whereDate('created_at', '>=',   date($request->init_date));
            }
            if ($request->has('fin_date') && !empty($request->fin_date)) {
                $list->whereDate('created_at', '<=',   date($request->fin_date));
            }
            if ($request->has('commerce') && !empty($request->commerce))

                $list->whereHas('getCommerce', function ($query) use ($request) {
                    $query->where('bussiness_name', 'like', '%' . $request->commerce . '%');
                });
            if ($request->has('pagos') && !empty($request->pagos))
                $list->where('payment_state', $request->pagos);
            if ($request->has('pedidos') && !empty($request->pedidos))
                $list->where('order_state', $request->pedidos);

            $list = $list->get();
        } else {
            $list = Order::where('commerce_id', $id)->where('state', 1)->orderBy('id', 'DESC')->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funGetListByCustomer(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id'
        ]);

        $id = $request->id;

        if ($id == 0) {
            $list = Order::where('state', 1)->with('getCommerce')->with('getAddress')->orderBy('created_at', 'ASC')->get();
        } else {
            $list = Order::where('customer_id', $id)->where('state', 1)->with('getCommerce')->with('getAddress')->orderBy('created_at', 'ASC')->get();
        }

        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id'
        ]);

        $element = Order::where('id', $request->id)->with('getOrderDetails.getProduct')->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }

    public function funCreate(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|exists:commerces,id',
            'customer_id' => 'required|exists:customers,id',
            //'reference' => 'required|exists:orders, id', //Reference
            //'date' => 'required|exists:orders, id',
            'payment_type_vp' => 'required',
            //'payment_state' => '',
            'coupon_id' => 'numeric|exists:coupons,id',
            'coupon_value' => 'numeric',
            'delivery_value' => 'required|numeric',
            'user_address_id' => 'required|exists:user_addresses,id',
            'tip_value' => 'required|numeric',
            'shipping_date' => 'required',
            'order_details' => 'required'
        ]);

        $reference = "";
        $date = Date('Y-m-d H:i:s');
        $customer = Customer::where('id', $request->customer_id)->first();
        $user = User::where('id', $customer->user_id)->first();
        $address = UserAddress::where('id', $request->user_address_id)->first();
        //Guardo el pedido
        //return response()->json(['code' => 200, 'data' => $user], 200);


        $order = new Order();
        if ($request->userDistributor_id) {
            $customer_distributor = Customer::where('user_id', $request->userDistributor_id)->first();
            if (empty($customer_distributor)) {
                $newCustomer = new Customer();
                $newCustomer->user_id = $request->userDistributor_id;
                $newCustomer->state = 2;
                $newCustomer->save();
                $order->customer_id = $newCustomer->id;
            } else {
                $order->customer_id = $customer_distributor->id;
            }
        } else {
            $order->customer_id = $request->customer_id;
        }

        $order->commerce_id = $request->commerce_id;
        $order->reference = $reference;
        $order->date = $date;
        $order->payment_type_vp = $request->payment_type_vp;
        $order->payment_state = $request->payment_state;
        $order->total = 0; //mientras creo la base de datos
        $order->coupon_value = $request->coupon_value;
        $order->coupon_id = $request->coupon_id;
        $order->delivery_value = $request->delivery_value;
        $order->user_address_id = $request->user_address_id;
        $order->address = $address->address;
        $order->tip_value = $request->tip_value;
        $order->time = $request->shipping_date;
        $order->order_state = 14; //Valor parametro
        if ($request->observation) {
            $order->observation = $request->observation;
        }
        if ($request->dateSelected) {
            $range_hour = RangeHour::where('id', $request->dateSelected['id'])->first();
            if ($range_hour->limit > 0) {
                $range_hour->limit = (($range_hour->limit) - 1);
                $range_hour->update();
            }
        }


        if ($order->save()) {
            if ($request->has('coupon_id')) {
                $cuponverified = cupones::find($request->coupon_id);
                $cuponverified->max_quantity--;
                $cuponverified->used++;
                $cuponverified->save();
            }
            $idOrder = $order->id;
            foreach ($request->order_details as $key => $value) {
                $response = $this->funAddDetailToOrder($value, $idOrder, $user->rol_id);
                if ($response['code'] != 200) { ///Verifico si hubo un error en las insercion
                    return response()->json($response, 430);
                }
            }

            //Recorres los order_details y crearlas ordenes
            $order->reference = 'FAV-' . $order->id;
            $order->sub_total = $this->funGetOrderSubTotalValue($idOrder);

            if ($request->distributor_id) {

                $distributor = Distributor::where('id', $request->distributor_id)->first();
                $DistributorComission = DistributorComissions::create([
                    'order_id' => $order->id,
                    'distributor_id' => $distributor->id,
                    'distributor_code' => $distributor->distributor_code,
                    'distributor_percent' => $distributor->distributor_percent,
                    'state' => 1
                ]);

            }else{
            if(!($request->userDistributor_id)){    
                if (!empty($customer->getDistributor)){


                    $DistributorComission = DistributorComissions::create([
                        'order_id' => $order->id,
                        'distributor_id' => $customer->getDistributor->id,
                        'distributor_code' => $customer->getDistributor->distributor_code,
                        'distributor_percent' => $customer->getDistributor->distributor_percent,
                        'state' => 1
                    ]);
                }
            }
        }
            // $order->order_state = 15; // En Solicitado
            if ($order->update()) {
                $orderData = $this->funUpdateOrderValue($idOrder);
                if ($orderData['code'] == 200) {

                    /*if (config('app.appMode') === 'prod') {
                        send_sms(
                            //'573205843122',
                            '573102098263,573103485811,573107792566,573188274080,573185176204',
                            //'573146399925',
                            'Nuevo pedido disponible en favores. (REF #' . $order->id . ')'
                        );
                    } else {
                        send_sms(
                            //'573205843122',
                            //'573102098263,573103485811,573107792566,573188274080,573185176204',
                            '573146399925',
                            'Nuevo pedido disponible en favores. (REF #' . $order->id . ')'
                        );
                    }*/

                    return response()->json(['code' => 200, 'data' => $orderData['data']], 430);
                }
                return ['code' => 430, 'data' => $order, 'message' => 'Se realizo el pedido, ocurrio un error al momento de calcular el valor total'];
            }
            return ['code' => 430, 'data' => $order, 'message' => 'La orden no puso pasar a solicitado'];
        }
    }

    public function funGetOrderSubTotalValue($idOrder)
    {
        $total = 0;
        $orderDetails = OrderDetail::where('order_id', $idOrder)->get();

        foreach ($orderDetails as $key => $value) {
            $total += $value['total_value'];
        }

        return $total;
    }

    public function funAddDetailToOrder($detail, $idOrder, $idRole)
    {
        $Validation = Validator::make($detail, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
            'observation' => 'nullable',
        ]);

        if ($Validation->fails()) {
            return [
                'code' => 404,
                'data' => null,
                'message' => $Validation->errors()->first()
            ];
        }

        $product = Product::where('id', $detail['product_id'])->first();

        if (!is_null($product->getMarketProduct) && !is_null($product->getMarketProduct->parent)) {
            $productCategory = ProductCategory::where('product_id', $product->getMarketProduct->parent)->with('getCategory.getCommerce')->first();
        } else {
            $productCategory = ProductCategory::where('product_id', $product->id)->with('getCategory.getCommerce')->first();
        }
        $commerce_type = $productCategory->getCategory->getCommerce->commerce_type_vp;

        $unit = null;
        if (!empty($commerce_type)) {
            if ($commerce_type == 9) {
                $productRestaurant = RestaurantProduct::where('product_id', $product->id)->first();
                $ingredientsValue = $this->funGetValueIngredients($detail['product_config']);
                if (!is_null($productRestaurant->discount)) {
                    $valueForProduct = ['code' => 200, 'data' => $productRestaurant->value - ($productRestaurant->value * $productRestaurant->discount / 100) + $ingredientsValue];
                } else {
                    $valueForProduct = ['code' => 200, 'data' => $productRestaurant->value + $ingredientsValue];
                }
            } elseif ($commerce_type == 10) {
                $unit = $product->getMarketProduct->getUnit->name;
                $valueForProduct = $this->funProductValueFromIdRole($detail['product_id'], $idRole);
            }
        }

        //Obtengo el valor que le corresponde al producto de acuerdo al perfil del cliente

        // return [
        //     'code' => 400,
        //     'data' => null,
        //     'message' => $valueForProduct
        // ];

        //Calculo el valor total para el detalle
        $totalValueDetail = $valueForProduct['data'] * $detail['quantity'];

        $newOrderDetail = new OrderDetail();
        $newOrderDetail->product_id = $detail['product_id'];
        $newOrderDetail->order_id = $idOrder;
        $newOrderDetail->name = $product->name;
        $newOrderDetail->value = $valueForProduct['data'];
        $newOrderDetail->quantity = $detail['quantity'];
        $newOrderDetail->unit = $unit;
        $newOrderDetail->total_value = $totalValueDetail;
        if (!is_null($detail['observation'])) {
            $newOrderDetail->observation = $detail['observation'];
        }
        if (!is_null($detail['product_config'])) {
            $newOrderDetail->product_config = json_encode($detail['product_config']);
        }
        // dd($newOrderDetail);

        if ($newOrderDetail->save()) {
            return [
                'code' => 200,
                'data' => $newOrderDetail
            ];
        }

        return [
            'code' => 400,
            'data' => null,
            'message' => 'Error al guardar el oderDetail'
        ];
    }

    public function funProductValueFromIdRole($idProduct, $idRole)
    {
        $precio_query = PriceList::where('products_id', $idProduct)->where('profile_vp', $idRole)->first();
        if (empty($precio_query)) {
            return ['code' => 404, 'data' => null, 'message' => 'El producto con id = ' . $idProduct . ', No tiene precio asignado para el rol seleccionado'];
        }
        if (!is_null($precio_query->discount)) {
            $precio = $precio_query->value - ($precio_query->value * $precio_query->discount / 100);
        } else {
            $precio = $precio_query->value;
        }
        return ['code' => 200, 'data' => $precio];
    }

    public function funUpdateOrderValue($idOrder)
    {
        $total = 0;
        $order = Order::where('id', $idOrder)->first();

        if (!empty($order)) {
            $total += $order->sub_total;
            $total -= $order->coupon_value;
            $total += $order->delivery_value;
            $total += $order->tip_value;

            $order->total = $total;

            if ($order->update()) {
                return ['code' => 200, 'data' => $order];
            } else {
                return ['code' => 530, 'data' => $total, 'message' => 'No se pudo actualizar el total de la orden'];
            }
        }
    }

    public function funGetValueIngredients($ingredients)
    {
        $total = 0;
        foreach ($ingredients as $ingredient_detail) {
            foreach ($ingredient_detail['get_ingredients'] as $ingredient) {
                $ingredientItem = Ingredient::where('id', $ingredient['ingredient_id'])->first();
                if ($ingredientItem->getCategory->category_type_vp == 6) {
                    $total += $ingredientItem->value * $ingredient['ingredient_quantity'];
                }
            }
        }
        return $total;
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id'
        ]);

        $element = Order::where('id', $request->id)->with('getOrderDetails')->first();
        $data = $request->input();
        if ($element->update($data)) {
            return response()->json(['code' => 200, 'data' => $element], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }

    public function funUpdateState(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'state' => 'required'
        ]);
        $order = Order::where('id', $request->id)->first();
        $order->order_state = $request->state;
        $responseApp = 200;

        $state = ParameterValue::where('id', $request->state)->first();
        $user = $order->getCustomer->getUser;
        if ($request->state - 1 == 14) {
            $address = UserAddress::where('id', $order->user_address_id)->first();
            $products = [];
            foreach ($order->getOrderDetails as $detail) {
                $product = [
                    'nombreProducto' => $detail->name,
                    'cantidad' => $detail->quantity,
                    'valorTotal' => $detail->total_value
                ];
                $products[] = $product;
            }
            $value = [
                'subtotal' => $order->sub_total,
                'descuento' => $order->coupon_value,
                'domicilio' => $order->delivery_value,
                'total' => $order->total
            ];
            $fechaentrega = $order->time;
            $date;
            if (strpos($fechaentrega, 'ene')) {

                $date = $this->dateFormated('ene.', '1', $fechaentrega);
            } elseif (strpos($fechaentrega, 'feb')) {

                $date = $this->dateFormated('feb.', '2', $fechaentrega);
            } elseif (strpos($fechaentrega, 'mar')) {

                $date = $this->dateFormated('mar.', '3', $fechaentrega);
            } elseif (strpos($fechaentrega, 'abr')) {

                $date = $this->dateFormated('abr.', '4', $fechaentrega);
            } elseif (strpos($fechaentrega, 'may')) {

                $date = $this->dateFormated('may.', '5', $fechaentrega);
            } elseif (strpos($fechaentrega, 'jun')) {

                $date = $this->dateFormated('jun.', '6', $fechaentrega);
            } elseif (strpos($fechaentrega, 'jul')) {

                $date = $this->dateFormated('jul.', '7', $fechaentrega);
            } elseif (strpos($fechaentrega, 'ago')) {

                $date = $this->dateFormated('ago.', '8', $fechaentrega);
            } elseif (strpos($fechaentrega, 'sep')) {

                $date = $this->dateFormated('sep.', '9', $fechaentrega);
            } elseif (strpos($fechaentrega, 'oct')) {

                $date = $this->dateFormated('oct.', '10', $fechaentrega);
            } elseif (strpos($fechaentrega, 'nov')) {

                $date = $this->dateFormated('nov.', '11', $fechaentrega);
            } elseif (strpos($fechaentrega, 'dic')) {

                $date = $this->dateFormated('dic.', '12', $fechaentrega);
            } else {
                $fechaentrega = substr($fechaentrega, 0, strpos($fechaentrega, '-'));
                $date = DateTime::createFromFormat('d/m/Y h:i A', $fechaentrega);
                $date = $date->format('Y-m-d H:i:s');
            }

            $makeDeliveryRoute = config('app.domiciliosApp') . 'api/newDeliveryService';

            // dd($makeDeliveryRoute);
            $response = Http::asForm()->post($makeDeliveryRoute, [
                'name' => $user->name,
                'last_name' => $user->last_name,
                'cellphone' => $user->cellphone,
                'finalAddress[address]' => $address->address,
                'finalAddress[lat]' => $address->lat,
                'finalAddress[lng]' => $address->lng,
                'finalAddress[observation]' => json_encode($products),
                'reference' => $order->reference,
                'date' => $date,
                'deliveryValue' => json_encode($value)
            ]);
            $responseApp = $response->json()['code'];
        }
        if ($order->update()) {

            $dataNotification = [
                "title"     => "Favores.co - Pedido",
                "message"   => $state->name,
                "type"      => 1 //Cambio de pedido
            ];
            $token = $user->token_firebase;

            $this->sendNotication($dataNotification, $token);
            
            if ($responseApp == 201) {
                return response()->json(['code' => 200, 'data' => null, 'message' => 'El domicilio ya existe'], 200);
            }

            return response()->json(['code' => 200, 'data' => $order, 'message' => 'Estado cambiado'], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null], 530);
        }
    }

    public function funDelete(Request $request)
    {
    }
    public function dateFormated($mesStr, $mesNum, $fechaentrega)
    {
        $poshoraentrega = strpos($fechaentrega, '|');
        $horaentrega = substr($fechaentrega, $poshoraentrega + 1);
        $horaentrega = substr($horaentrega, 0, strpos($horaentrega, '-') - 1);

        $fechaentrega = substr($fechaentrega, 0, $poshoraentrega);
        $fechaentrega = str_replace($mesStr, $mesNum, $fechaentrega);
        $fechaentrega = str_replace(" ", "/", $fechaentrega);
        $fechaentrega = str_replace("|", " ", $fechaentrega);
        $fechaentrega = $fechaentrega . ' ' . $horaentrega;

        $date = DateTime::createFromFormat('d/m/Y h:i A', $fechaentrega);
        $date = $date->format('Y-m-d H:i:s');
        return $date;
    }

    public function updateStateDomi(Request $request) {

        $state = $request->state;
        $reference = $request->reference;

        $element = Order::where('reference', $reference)->first();

        if(!empty($element)) {

            $element->order_state = $state;

            if($element->update()) {

                return response()->json(['code' => 200, 'message' => 'Estado cambiado'], 200);

            }else{
                return response()->json(['code' => 530, 'data' => null], 530);
            }

        }else{
            return response()->json(['code' => 530, 'data' => null], 530);
        }

    }

}
