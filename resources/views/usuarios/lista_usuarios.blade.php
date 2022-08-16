@extends('voyager::master')

@section('content')

@section('page_header')

<h6 class="page-title">
    {{-- <i class="fa fa-truck-pickup"></i> --}}
    Lista usuarios
</h6>
@stop

<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <table id="tableUser" class="display compact cell-border" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Iniciales</th>
                        {{-- <th>Role</th> --}}
                        <th>Opc</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->iniciales}}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 

@endsection
@section('javascript')
<script src="{{ asset('public/js/usuarios/lista_usuarios.js') }}?v=0.0.2"></script>
@stop
