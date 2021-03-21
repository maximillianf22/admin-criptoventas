<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>
    <style>
        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .contenido {
            font-size: 14px;
        }

        #primero {
            background-color: #ccc;
        }

        #segundo {
            color: #44a359;
        }

        #tercero {
            text-decoration: line-through;
        }

        h4 {
            margin-top: 2px;
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <div class="contenido">
        <div style="border:1px solid #333; padding: 5px 10px;">
            <table border="0" cellpadding="0" width="100%">
                <tr>
                    <td width="25%">
                        <img style="height:90px ; width: 90px " src="{{config('app.myUrl').'storage/'. $data->getCommerce->getUser->photo}}" alt="">
                    </td>
                    <td width="25%" style="padding-bottom: 10px;margin-bottom: 0">
                        <h2 style="text-align: center">{{$data->getCommerce->bussiness_name}}

                        </h2>
                        <h3 style="text-align: center;margin: 0 ;color:gray">

                            NIT:{{$data->getCommerce->nit}}
                        </h3>
                        <h3 style="text-align: center;margin: 0 ;color:gray">

                            celular:{{$data->getCommerce->getUser->cellphone}}
                        </h3>
                    </td>
                    <td width="25%">
                        <h3>Orden Compra : {{$data->reference}}
                        </h3>
                    </td>
                </tr>

            </table>
        </div>

    </div>



    <div class="contenido">
        <div style="border:1px solid #333; padding: 5px 10px;">
            <table border="0" cellpadding="0" width="100%">
                <tr>
                    <td width="50%">
                        <b style="font-size:8px;color:#999">CLIENTE </b><br /><span style="font-size:14px"> {{$data->getCustomer->getUser->name.' '.$data->getCustomer->getUser->last_name}}</span>
                    </td>
                    <td width="25%">
                        <b style="font-size:8px;color:#999">TELEFONO / CELULAR </b><br /><span style="font-size:14px">{{$data->getCustomer->getUser->cellphone}}</span>
                    </td>
                    <td width="25%">
                        <b style="font-size:8px;color:#999">EMAIL </b><br /><span style="font-size:14px"> {{$data->getCustomer->getUser->email}}</span>
                    </td>
                </tr>
            </table>

            <table border="0" cellpadding="0" width="100%">
                <tr>
                    <td width="50%">
                        <b style="font-size:8px;color:#999">DIRECCION </b><br /><span style="font-size:14px">{{$data->getAddress->address}}</span>
                    </td>
                    <td width="25%">
                        <b style="font-size:8px;color:#999">FECHA ENTREGA Y HORA DE ENTREGA</b><br /><span style="font-size:14px">{{$data->time}}</span>
                    </td>

                </tr>
            </table>

            <table border="0" cellpadding="0" width="100%">
                <tr>
                    <td width="100%">
                        <b style="font-size:8px;color:#999">COMENTARIOS </b><br /><span style="font-size:14px">--</span>
                    </td>
                </tr>

            </table>

        </div>

    </div>

    <div style="text-align: left; padding: 5px; font-family: tahoma; font-size: 14px;"><b>Detalle de la Compra </b></div>

    <!-- detalle de la compra --->
    <div style="padding: 10px;">






        <div class='panel-products-view' id='pnl-products-car' data-cantidad='".$items_."' style='font-size:10pt'>

            <table width='100%'>
                @foreach ($data->getOrderDetails as $item)

                <tr>
                    <td width='50%'>
                        <h4>{{$item->name}} <small> {{ !is_null($item->unit) ? "(1x $item->unit)" : '' }} </small> x({{$item->quantity}})</h4>
                        <div style="overflow: auto; max-height: 20vh;">
                            @if(!is_null($item->product_config) && !is_null(json_decode($item->product_config)) && is_array(json_decode($item->product_config)) > 0)
                            @foreach(json_decode($item->product_config) as $ingredientCat)
                            <span style="color: #999">*{{$ingredientCat->category_name}}</span>
                            @foreach($ingredientCat->get_ingredients as $ingredient)
                            <span style="color: #999">*{{$ingredient->ingredient_name}} (x{{$ingredient->ingredient_quantity}})</span>
                            @endforeach
                            @endforeach
                            @endif
                        </div>

                    </td>

                    <td style='text-align:right'>
                        ${{number_format($item->total_value)}}
                    </td>
                </tr>
                @endforeach
            </table>

        </div><br />
        <hr />



        <table width='100%' style='font-size:10pt'>
            <tr>
                <td width='70%'></td>
                <td width='15%'>Subtotal</td>
                <td width='15%'>${{number_format($data->sub_total)}} </td>
            </tr>
            <tr>
                <td width='70%'></td>
                <td width='15%'>Descuento :</td>
                <td width='15%'>-${{number_format($data->coupon_value)}}</td>
            </tr>
            <tr>
                <td width='70%'></td>
                <td width='15%'>Propina :</td>
                <td width='15%'>${{number_format($data->tip_value)}}</td>
            </tr>
            <tr>
                <td width='70%'></td>
                <td width='15%'>Costo de Envio :</td>
                <td width='15%'>
                    $ {{number_format($data->delivery_value)}}
                </td>
            </tr>
            <tr>
                <td width='70%'></td>
                <td width='15%'>Total Compra :</td>
                <td width='15%'>${{number_format($data->total)}} </td>
            </tr>

        </table>





    </div>
    <!--- End detail ------>



</body>


</html>