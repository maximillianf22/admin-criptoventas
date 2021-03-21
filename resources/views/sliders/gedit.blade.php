@extends('layouts.app', ['page' => 'Sliders'])

@section('content')
<div class="header bg-gradient-info pb-7 pt-5 pt-md-7">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            
                            <li class="breadcrumb-item"><a href="{{route('gslider.index')}}">Sliders</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7">
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        - {{$error}} <br>
        @endforeach
    </div>
    @endif
    @if (Session::has('success'))
    <div class="alert alert-info">
        {{ session('success') }}
    </div>
    @endif


    <div class="row mt-5">
        <div class="col-8">
            <div class="card shadow">
                <img src="{{asset('storage/'.$slider->url)}}">
            </div>
        </div>
        <div class="col-4">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">Actualizar slider</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('gslider.update',$slider->id)}}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="hidden" name="id" value="{{$slider->id}}">
                            <input type="text" name="name" value="{{$slider->name}}" id="input-name" class="form-control form-control-alternative" placeholder="Nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Imagen *<small style="font-size: .7rem">Tamaño recomendado: 350 alto x 1355 ancho</small></label>
                            <input type="file" name="url" class="form-control" id="url" lang="en">
                        </div>
                        <div class="form-group">
                            <label>Link de redireccionamiento</label>
                            @isset($slider->redirect_url)
                            <input style="font-size: .8rem;" type="url" placeholder="Debe tener la forma: http://ejemplo.com" name="redirect_url" class="form-control" value="{{$slider->redirect_url}}">
                            @else
                             <input style="font-size: .8rem;" type="url" placeholder="Debe tener la forma: http://ejemplo.com" name="redirect_url" class="form-control">
                            @endisset
                        </div>
                        <div class="form-group">
                            <label>Estado *</label>
                            <select name="state" class="form-control">
                                @foreach(Config::get('const.user_states') as $state => $value)
                                <option value="{{$state}}" {{$slider->state == $state ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4" onclick="validateURL()">Actualizar slider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">
    function validateURL(){
        let str = $('input[name=redirect_url').val()
        if(!(str == "")){
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        if (!(!!pattern.test(str))){
            Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'URL redireccionamiento escrito de una manera incorrecta!',
            text: 'Link de redireccionamiento mal escrito, puede que este funcione de una manera incorrecta',
            showConfirmButton: false,
             timer: 4000
         })
        }
  }
    }
    
</script>
@endsection

@push('js')

@endpush