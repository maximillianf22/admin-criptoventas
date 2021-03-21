@extends('layouts.app', ['page' => 'Categorías de comercios'])

@section('content')
<div class="header bg-gradient-info pb-5 pt-5">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col text-right">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('administrator.home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('category.index')}}">Categorías de comercios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt--7 mb-3">
    <div class="row mt-4">
        <div class="col-4">
            <div class="card shadow">
                <img src="https://riojanorth.com/wp-content/themes/adventure-tours/assets/images/placeholder.png" class="card-img-top imgUpdate">
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
            @if (Session::has('success'))
            <div class="alert alert-info">
                {{ session('success') }}
            </div>
            @endif
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="mb-0">Editar categoría</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('category.update',$category->id)}}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('put')
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Nombre *</label>
                                        <input type="hidden" name="id" value="{{$category->id}}">
                                        <input type="text" name="name" value="{{$category->name}}" id="input-name" class="form-control form-control-alternative" placeholder="Nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipo de comercio *</label>
                                        <select class="form-control" name="commerce_type_vp" id="commerce_id" required>
                                            @foreach ($type as $item)
                                            <option {{$category->commerce_type==$item->id?'selected':''}} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Estado *</label>
                                        <select name="state" class="form-control" required>
                                            @foreach(Config::get('const.states') as $state => $value)
                                            <option value="{{$state}}" {{$category->state == $state ? 'selected' : ''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Descripción *</label>
                                        <textarea name="description" class="form-control" rows="11" placeholder="Descripción" id="exampleFormControlTextarea1" rows="3" required>{{$category->description}} </textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label>Imagen (Opcional)</label>
                                <input type="file" name="photo" class="form-control inputImg">
                            </div> -->
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info mt-4">Actualizar categoría</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush