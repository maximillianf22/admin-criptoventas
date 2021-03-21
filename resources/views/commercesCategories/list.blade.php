@extends('layouts.app', ['page' => 'Categorías de comercios'])

@section('content')
<div class="header bg-gradient-info pb-7 pt-5 pt-md-7">
    <div class="container-fluid">
    </div>
</div>

<div class="container-fluid mt--7">
    <div class="row mt-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">cats</h3>
                        </div>
                        <div class="col text-right">
                            <a href="{{route('categories.create')}}" class="btn btn-sm btn-primary">Comercios</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <!-- <th></th> -->
                                <th scope="col">Nombre del negocio</th>
                                <th scope="col">nit</th>
                                <th scope="col">estado</th>
                                <th scope="col">Categorias</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($commerces)
                            @foreach($commerces as $cat)
                            <tr id="{{$cat->id}}">
                                {{--
                                <td>
                                @if(Storage::disk('public')->exists('categories_img/'.$cat->photo))
                                <img class="profile-user-img img-fluid mx-auto d-block " width="100" src="{{ asset('storage/categories_img/'.$cat->photo) }}" alt="User profile picture">
                                @else
                                <img class="profile-user-img img-fluid mx-auto d-block " width="100" src="https://scotturb.com/wp-content/uploads/2016/11/product-placeholder.jpg" alt="User profile picture">
                                @endif
                                </td>
                                --}}
                                <td>{{$cat->bussiness_name}} </td>
                                <td>{{$cat->nit }}</td>
                                <td>{{$cat->state==1?'Activado':'Desactivado' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{route('category.show', [$cat->id])}}"><i class="fas fa-edit"></i>Categorias</a>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush