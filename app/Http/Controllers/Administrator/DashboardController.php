<?php

namespace App\Http\Controllers\Administrator;

use App\Commerce;
use App\Customer;
use App\Distributor;
use App\Exports\OrderDetailsExport;
use App\Exports\OrderExport;
use App\Exports\OrdersDistributorExport;
use App\Http\Controllers\Controller;
use App\Order;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $commerce = Commerce::where('state', 1)->get();
        $orders = Order::where('state', 1)
            ->whereDate('created_at',  date('Y-m-d'))
            ->where('order_state', '<>', 23)
            ->Where(function ($query) {
                $query->Where('payment_state', 20)
                    ->orWhere('payment_state', 22);
            });

        $orderCount = $orders->count();
        $orderTotal = $orders->sum("total");

        $usersCount = User::where(DB::raw('DATE(created_at)'), date('Y-m-d'))
            ->count();
        $commerceCount = User::where(DB::raw('DATE(created_at)'), date('Y-m-d'))
            ->count();
        $distributorCount = User::where(DB::raw('DATE(created_at)'), date('Y-m-d'))
            ->count();
        return view('dashboard.index', compact('distributorCount', 'commerceCount', 'usersCount', 'orderTotal', 'orderCount', 'commerce'));
    }
    public function reportes(Request $request)
    {
        $type = $request->type;
        $start = $request->start;
        $end = $request->end;
        switch ($type) {
            case '0':
                $reporte = new OrderExport();
                if ($request->commerce != 0)
                    $reporte->commerce($request->commerce);
                return $reporte->rangeDate($start, $end)
                    ->download('VentasComercios.xlsx');
                break;
            case '1':
                $reporte = new OrderDetailsExport();
                $reporte->category($request->category);
                return $reporte->rangeDate($start, $end)
                    ->download('ventasCategorias.xlsx');
                break;
            case '2':
                $reporte = new OrdersDistributorExport();
                if ($request->commerce != 0)
                    $reporte->commerce($request->commerce);
                return $reporte->rangeDate($start, $end)
                    ->download('VentasDistribuidores.xlsx');
                break;

            default:

                break;
        }
    }
    public function contadores(Request $request)
    {
        $start = date($request->start);
        $end = date($request->end);
        $query = null;
        switch ($request->type) {

                //antes de criticar mi codigo el case 0 lo hice de ultimo y para no otro metodo lo agregue aqui
            case '0':

                $ventas = Order::where('state', 1)
                    ->where('order_state', '<>', 23)
                    ->Where(function ($query) {
                        $query->Where('payment_state', 20)
                            ->orWhere('payment_state', 22);
                    })
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);

                if ($request->commerce != 0)
                    $ventas->where('commerce_id', $request->commerce);

                return   response()->json(['message' => 'exito en la busqueda', 'total' => $ventas->sum('total'), 'count' => $ventas->count()], 200);
                break;
            case '1':
                $query = User::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->count();
                break;
            case '2':
                $query = Commerce::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->count();
                break;
            case '3':
                $query = Distributor::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->count();
                break;
            case '4':
                $query = Customer::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->count();
                break;

            default:
                return response()->json(['message' => 'Tipo de busqueda aun no soportada'], 400);
                break;
        }
        return response()->json(['message' => 'exito en la busqueda', 'count' => $query], 200);
    }
}
