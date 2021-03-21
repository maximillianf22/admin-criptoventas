@extends('layouts.app', ['page' => 'Sliders'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('slider.index')}}">Sliders</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Listado</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--7 mb-3">
    <div class="row mt-5">
        <div class="col-4">
            <div class="card shadow bg-secondary">
                <div class="card-header">
                    <h3 class="mb-0">Crear Slider</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('sliders.store') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <input type="hidden" id="idcommercio" name="commerce_id" value="{{$commerce->id}}">
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="text" class="form-control" name="name" value = "{{old('name')}}" id="name" placeholder="Ingrese nombre del slider">
                        </div>
                        <div class="form-group">
                            <label>Imagen *<small style="font-size: .7rem">Tamaño recomendado: 208 alto x  370 ancho</small></label>
                            <input type="file" name="url" value = "{{old('url')}}" class="form-control" lang="en">
                        </div>
                        <div class="form-group">
                            <label>Link de redireccionamiento</label>
                            <input style="font-size: .8rem;" type="url" name="redirect_url" class="form-control" value = "{{old('redirect_url')}}" placeholder="Debe tener la forma: http://ejemplo.com">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" onclick="validateURL()"> Crear Slider</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                - {{$error}} <br>
                @endforeach
            </div>
            @endif
            @if(session('status'))
                    <div class="alert alert-info" role="alert">
                      {{session('status')}}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span>
                      </button>
                    </div>
@endif

            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Sliders del comercio: {{$commerce->bussiness_name}}</h3>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th></th>
                                <th scope="col">Nombre</th>
                                <th scope="col">estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commerce->getSliders as $item)
                            @if($item->state == 1 || $item->state == 0)
                            <tr id="{{$item->id}}">
                                <td>
                                    @if(Storage::disk('public')->exists($item->url))
                                    <img class="profile-user-img mx-auto d-block" src="{{ asset('storage/'.$item->url) }}" width="150" alt="User profile picture">
                                    @else
                                    <img class="profile-user-img mx-auto d-block" src="https://riojanorth.com/wp-content/themes/adventure-tours/assets/images/placeholder.png" width="150" alt="User profile picture">
                                    @endif
                                </td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->state==1?'Activo':'Inactivo' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{route('slider.edit', [$item->id])}}"><i class="fas fa-edit"></i>editar</a>
                                    <button class="btn btn-sm btn-danger btnDeleteSlider"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal -->
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
  }else{

  }
    }
    
</script>
@endsection

@push('js')

@endpush