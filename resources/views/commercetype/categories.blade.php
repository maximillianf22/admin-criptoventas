@extends('layouts.app')

@section('content')
<div class="header bg-gradient-info pb-7 pt-5 pt-md-7">
    <div class="container-fluid">
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Comercio: {{$commerce->bussiness_name}}</h3>
                </div>
                <div class="col text-right">

                </div>
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                - {{$error}} <br>
                @endforeach
            </div>
            @endif
            @isset($success)
            <div class="alert alert-succes">

                {{ $success}}
            </div>
            @endisset
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <form action="{{ route('commerces.category.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <h5 class="card-title"></h5>
                                <input type="hidden" id="idcommercio" name="commerce" value="{{$commerce->id}}">

                                <div class="row">
                                    <div class="col">

                                        <div class="form-group">
                                            <label for="category_id">categorias</label>
                                            <select class="form-control" name="category_id" id="category_id">
                                                @foreach ($categories as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <button type="submit" class="btn btn-primary btn-block"> <i class="fat-add"></i> Añadir Categoria</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-8">

                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>

                                    <th scope="col">Nombre</th>
                                    <th scope="col">estado</th>
                                    <th scope="col">Descripcion</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($commerce->getCategories as $item)
                                <tr id="{{$item->id}}">
                                    <td></td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->state==1?'Activado':'Desactivado' }}</td>
                                    <td>{{$item->description }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger btnDeleteforCategories"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                @endforeach



                            </tbody>
                        </table>


                        <!-- Modal -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush