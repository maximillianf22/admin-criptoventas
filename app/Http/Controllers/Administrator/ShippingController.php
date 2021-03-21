<?php

namespace App\Http\Controllers\Administrator;

use App\Commerce;
use App\Http\Controllers\Controller;
use App\RangeHour;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commerces = Commerce::where('state', 1)->get();
        $data = array(
            'commerces' => $commerces
        );
        return view('deliveryHours.index', $data);
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
        $store = $this->funCreate($request);
        $hours = [];
        if ($store->original['code'] == 200) {
            $hours = $this->funGetList($request)->original['data'];
        }

        $day = $request->weekDay;
        $view = view('deliveryHours.details', compact('hours', 'day'))->render();
        return response()->json(['code' => 200, 'data' => $view], 200);
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
        $hour = $this->funShow(request());
        return $hour;
    }

    public function showByCommerce(Request $request)
    {
        $hoursList = $this->funShowByCommerceByWeekDay($request);
        $hours = [];
        if ($hoursList->original['code'] == 200) {
            $hours = $hoursList->original['data'];
        }
        $day = $request->weekDay;
        $view = view('deliveryHours.details', compact('hours', 'day'))->render();
        return response()->json(['code' => 200, 'data' => $view], 200);
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
        // dd($request->all());
        $update = $this->funUpdate($request);
        $hours = [];
        if ($update->original['code'] == 200) {
            $request->merge(['commerce_id' => $update->original['data']->commerce_id]);
            $hours = $this->funGetList($request)->original['data'];
        }
        $day = $request->weekDay;
        $view = view('deliveryHours.details', compact('hours', 'day'))->render();
        return response()->json(['code' => 200, 'data' => $view], 200);
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
        $delete = $this->funDelete(request());
        if ($delete->original['code'] == 200) {
            request()->merge(['commerce_id' => $delete->original['data']->commerce_id]);
            $hours = $this->funGetList(request())->original['data'];
        }
        $day = request()->weekDay;
        $view = view('deliveryHours.details', compact('hours', 'day'))->render();
        return response()->json(['code' => 200, 'data' => $view], 200);
    }

    //FUNCIONES

    public function funGetList(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|exists:commerces,id',
            'weekDay' => 'required'
        ]);

        $hours = RangeHour::selectRaw('id, commerce_id, week_day, `limit`, `limit_pd`, time_format(init_hour, "%h:%i %p") as init_hour, time_format(fin_hour, "%h:%i %p") as fin_hour, state')
            ->where('commerce_id', $request->commerce_id)
            ->where('week_day', $request->weekDay)
            ->where('state', '<>', 2)
            ->get();

        return response()->json(['code' => 200, 'data' => $hours], 200);
    }

    public function funCreate(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'commerce_id' => 'required|exists:commerces,id',
            'weekDay' => 'required',
            'hora_inicial' => 'required',
            'hora_final' => 'required',
            'limit' => 'nullable'
        ]);

        $hour = new RangeHour();
        $hour->commerce_id = $request->commerce_id;
        $hour->week_day = $request->weekDay;
        $hour->init_hour = $request->hora_inicial;
        $hour->fin_hour = $request->hora_final;
        $hour->limit = is_null($request->limit) ? -1 : $request->limit;
        $hour->limit_pd = is_null($request->limit) ? -1 : $request->limit;
        if ($hour->save()) {
            return response()->json(['code' => 200, 'data' => $hour], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Ocurrio un problema al crear el horario'], 530);
        }
    }

    public function funShow(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id' => 'required|exists:range_hours,id'
        ]);
        $hour = RangeHour::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $hour], 200);
    }

    public function funShowByCommerceByWeekDay(Request $request)
    {
        $request->validate([
            'commerce_id' => 'required|exists:commerces,id',
            'weekDay' => 'required'
        ]);

        $hours = RangeHour::selectRaw('id, commerce_id, week_day, `limit`,`limit_pd`,time_format(init_hour, "%h:%i %p") as init_hour, time_format(fin_hour, "%h:%i %p") as fin_hour, state')
            ->where('commerce_id', $request->commerce_id)
            ->where('week_day', $request->weekDay);
        if ($request->api == 1) {
            $today = date('w') == 0 ? 7 : date('w');
            $hours->where('state', 1);
            if ($today == $request->weekDay) {
                $hours->where('fin_hour', '>=', date('H:m'));
            }
        } else {
            $hours->where('state', '<>', 2);
        }
        return response()->json(['code' => 200, 'data' => $hours->get()], 200);
    }

    public function funUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:range_hours,id',
            'hora_inicial' => 'required',
            'hora_final' => 'required',
            'limit' => 'nullable',
            'limit_pd' => 'nullable',
            'state' => 'required'
        ]);

        $hour = RangeHour::where('id', $request->id)->first();
        $hour->init_hour = $request->hora_inicial;
        $hour->fin_hour = $request->hora_final;
        if($hour->limit_pd == $request->limit_pd){
            $hour->limit_pd = is_null($request->limit_pd) ? -1 : $request->limit_pd; 
        }else{
            $hour->limit_pd = is_null($request->limit_pd) ? -1 : $request->limit_pd;
            $hour->limit = is_null($request->limit_pd) ? -1 : $request->limit_pd;
        }
        $hour->state = $request->state;

        if ($hour->update()) {
            return response()->json(['code' => 200, 'data' => $hour], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Ocurrio un problema al crear el horario'], 530);
        }
    }

    public function funDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:range_hours,id'
        ]);
        $hour = RangeHour::where('id', $request->id)->first();
        $hour->state = 2;
        if ($hour->update()) {
            return response()->json(['code' => 200, 'data' => $hour], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Ocurrio un problema al eliminar el horario'], 530);
        }
    }
}
