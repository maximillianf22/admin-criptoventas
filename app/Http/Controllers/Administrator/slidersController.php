<?php

namespace App\Http\Controllers\Administrator;

use App\Commerce;
use App\Http\Controllers\Controller;
use App\Slider;
use App\GSlider;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class slidersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }
    public function view($id)
    {

        request()->merge(['id' => $id]);
        request()->validate(['id' => 'exists:commerces,id']);
        $commerce = Commerce::find($id);
        return view('commerces.sliders', ['commerce' => $commerce]);
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
        $code = $this->funCreate($request)->getData()->code;
        if ($code == 200) {
            return back()->with('status', 'Slide creado con éxito!');
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

        $data = $this->funShow(request())->original;
        
        $commerce = Commerce::where('id', $data['data']->commerce_id)->first();
        $commerce_id = $commerce->id;

        if ($data['code'] == 200) {
            return view('commerces.slider_edit', ['slider' => $data['data'], 'commerce_id' => $commerce_id]);
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
        $data = $this->funUpdate($request)->original;
        if ($data['code'] == 200) {
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
        $this->funDelete(request());
    }
    public function funGetList(Request $request)
    {
        $request->validate([
            'commerce_id' => 'exclude_if:id,0|required|exists:sliders,id'
        ], [
            'commerce_id.required' => 'Debe enviar el id que desea buscar',
            'commerce_id.exists' => 'No se encontraron registros con searchID'
        ]);

        $id = $request->id;
        if ($id == 0) {
            $list = Slider::where('state', 1)->get();
        } else {
            $list = Slider::where('commerce_id', $id)->where('state', 1)->get();
        }
        return response()->json(['code' => 200, 'data' => $list], 200);
    }
    public function funGetListG()
    {
            $list = GSlider::where('state', 1)->get();
      
        return response()->json(['code' => 200, 'data' => $list], 200);
    }

    public function funShow(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sliders,id'
        ], [
            'id.required' => 'Debe enviar el id que desea buscar',
            'id.exists' => 'No se encontraron registros con searchID'
        ]);
        $element = Slider::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }
    public function funShowG(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sliders,id'
        ], [
            'id.required' => 'Debe enviar el id que desea buscar',
            'id.exists' => 'No se encontraron registros con searchID'
        ]);
        $element = GSlider::where('id', $request->id)->first();
        return response()->json(['code' => 200, 'data' => $element], 200);
    }
    public function funCreate(Request $request, $type = 0)
    {
        if($type == 1){
             $request->validate([
            'name' => 'required|max:499',
            'url' => 'required|image|mimes:jpg,jpeg,png'
        ]);
        }else{
             $request->validate([
            'name' => 'required|max:499',
            'url' => 'required|image|mimes:jpg,jpeg,png',
            'commerce_id' => 'required|exists:commerces,id'
        ]);
        }
       
        if($type == 1){

        $newSlider = new GSlider();
        $newSlider->name = $request->name;
       
        if ($request->url) {
            $newSlider->url = $request->url->store('sliders_img', 'public');
        }
        if($request->redirect_url){
            $newSlider->redirect_url = $request->redirect_url;
        }
        }else{
        $newSlider = new Slider();
        $newSlider->name = $request->name;
        $newSlider->commerce_id = $request->commerce_id;
        if ($request->url) {
            $newSlider->url = $request->url->store('sliders_img', 'public');
        }
        if($request->redirect_url){
            $newSlider->redirect_url = $request->redirect_url;
        }

        }

        if ($newSlider->save()) {
            return response()->json(['code' => 200, 'data' => $newSlider], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al crear'], 530);
        }
    }
    
    public function funUpdate(Request $request, $type = 0)
    {

        $request->validate([
            'name' => 'required|max:499',
            'url' => 'image|mimes:jpg,jpeg,png',
            'id' => 'required|exists:sliders,id'
        ]);
        if($type == 1){
        $Slider = GSlider::where('id', $request->id)->first();
        $Slider->name = $request->name;
        $Slider->state = $request->state;
        $Slider->redirect_url = $request->redirect_url;            
        if ($request->url) {
            Storage::disk("public")->delete($Slider->url);
            $Slider->url = $request->url->store('sliders_img', 'public');
        }
    }else{
        $Slider = Slider::where('id', $request->id)->first();
        $Slider->name = $request->name;
        $Slider->state = $request->state;
        if ($request->url) {
            Storage::disk("public")->delete($Slider->url);
            $Slider->url = $request->url->store('sliders_img', 'public');
        }
          if($request->redirect_url){
            $Slider->redirect_url = $request->redirect_url;
        }
    }
        

        if ($Slider->update()) {
            return response()->json(['code' => 200, 'data' => $Slider], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al actualizar'], 530);
        }
    }

    public function funDelete(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:sliders,id'
        ]);

        $model = Slider::where('id', $request->id)->first();
        $model->state = 2;

        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
    public function funDeleteG(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:sliders,id'
        ]);

        $model = GSlider::where('id', $request->id)->first();
        $model->state = 2;

        if ($model->update()) {
            return response()->json(['code' => 200, 'data' => $model], 200);
        } else {
            return response()->json(['code' => 530, 'data' => null, 'message' => 'Error al eliminar'], 530);
        }
    }
    public function slidersCommerce(){
     $commerce = Commerce::where('user_id', Auth::user()->id)->first();
        return view('sliders.index', compact(['commerce']));

    }
    public function editSliderCommerce($id){
        request()->merge(['id' => $id]);

        $data = $this->funShow(request())->original;

        if ($data['code'] == 200) {
            return view('sliders.edit', ['slider' => $data['data']]);
        }
    }
    public function updateSliderCommerce(Request $request, $id)
    {

        $request->merge(['id' => $id]);
        $data = $this->funUpdate($request)->original;
        if ($data['code'] == 200) {
            return redirect()->route('slider.index')->with('status', 'Slide actualizado con éxito!');
        }
    }

    public function slidersGlobal(){
        $gsliders = GSlider::all();
        return view('sliders.gindex', compact(['gsliders']));
    }
    public function editSliderGlobal($id){
    request()->merge(['id' => $id]);

        $data = $this->funShowG(request())->original;

        if ($data['code'] == 200) {
            return view('sliders.gedit', ['slider' => $data['data']]);
        }
    }
    public function storeSliderGlobal(Request $request){
        $code = $this->funCreate($request, 1)->getData()->code;
        if ($code == 200) {
            return back()->with('status', 'Slide creado con éxito!');
        }
    }

    public function updateSliderGlobal(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $data = $this->funUpdate($request, 1)->original;
        if ($data['code'] == 200) {
            return redirect()->route('gslider.index')->with('status', 'Slide actualizado con éxito!');;
        }
    }

}
