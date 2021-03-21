@extends('layouts.app')

@section('content')
<section class="content-wrapper">
    <div class="content-header-section ">
        <div class="title-section float-left">Administrar minimo de compra por perfil</div>
    </div>
    <div class="content-body pad-all " style="padding:10px 10px 1px; margin:10px 20px 2px;">
        <div class="container-fluid pad-all">
        </div>
        <div class="row">
            <div class="col-6 box-content-section">
                <form action="{{route('MinimumShoppingValue.saveMinCompra')}}" autocomplete="off" method="post">
                    @csrf
                    <table id="result" class="table table-responsive table-app-content">
                        <thead>
                            <tr>
                                <th class="text-center">Perfil</th>
                                <th class="text-center">Mínimo de compra</th>
                            </tr>
                        </thead>
                        <tbody class="store-b-manager">
                            @foreach($perfiles as $perfil)
                            <tr>
                                <td>
                                    <p class="m-0 text-dark">{{$perfil->name}}</p>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="minimos[{{$perfil->id}}]" value="{{$perfil->minimo}}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-info float-right btn-lg">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
