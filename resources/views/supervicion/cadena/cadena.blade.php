@extends('voyager::master')

@section('content')

@section('page_header')

<h6 class="page-title">
    <i class="fa fa-truck-pickup"></i>
    Cadena de custodia 
</h6>
@stop

<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <table id="tableCadena" class="display compact cell-border" style="width:100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Folio Servicio</th>
                        <th>Fecha Muestreo</th>
                        <th>Fecha Recepcion</th>
                        <th>Cliente</th>
                        <th>Norma</th> 
                        <th>Estado</th>
                        <th>Fecha creación</th>
                        <th>Creado Por</th>
                        <th>Fecha Actualizacion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{$item->Id_solicitud}}</td>
                            <td>{{$item->Folio_servicio}}</td>
                            <td>{{$item->Fecha_muestreo}}</td>
                            <td>{{$item->Fecha_recepcion}}</td>
                            <td>{{$item->Empresa_suc}}</td>
                            <td>{{$item->Nor_sub}}</td>
                            <td>{{$item->Estado}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->Id_user_c}}</td>
                            <td>{{$item->updated_at}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 

@endsection

@section('javascript')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="{{ asset('public/js/supervicion/cadena/cadena.js') }}?v=0.0.3"></script>
@stop
