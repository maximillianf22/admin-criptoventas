<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Module;
use App\Permit;
use App\Rol;
use Illuminate\Http\Request;

class PermitsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = [];
        $response = $this->funGetList();
        if ($response->original['code'] == 200) {
            $roles = $response->original['data'];
        }
        $modules = Module::where('state', 1)->get();
        $data = array(
            'roles' => $roles,
            'modules' => $modules
        );
        return view('permits.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id'
        ]);

        foreach ($request->permits as $id => $value) {
            $permit = Permit::where('module_id', $id)->where('rol_id', $request->rol_id)->first();
            // dd($request->permits, $id, $request->rol_id, $permit);
            if ($value == "1") {
                if (is_null($permit)) {
                    $newPermit = new Permit();
                    $newPermit->module_id = $id;
                    $newPermit->rol_id = $request->rol_id;
                    $newPermit->save();
                } else {
                    $permit->state = 1;
                    $permit->update();
                }
            } else if ($value == "0" && !is_null($permit)) {
                $permit->state = 0;
                $permit->update();
            }
        }
        return back()->with('success', 'Permisos actualizados');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        request()->merge(['idRol' => $id]);
        return $this->funGetPermits(request());
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

    public function funGetList()
    {
        $roles = Rol::where('unique', 1)->where('state', '<>', 2)->get();
        return response()->json(['code' => 200, 'data' => $roles], 200);
    }

    public function funGetPermits(Request $request)
    {
        $request->validate([
            'idRol' => 'required|exists:roles,id'
        ]);
        $permits = Permit::where('rol_id', $request->idRol)->where('state', 1)->with('getModule')->get();
        return response()->json(['code' => 200, 'data' => $permits], 200);
    }
}
