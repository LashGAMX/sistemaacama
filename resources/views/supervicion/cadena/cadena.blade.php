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
                        <th>Id<br><input type="text" class="column-filter" data-column="0" style="width:50px"></th>
                        <th>Folio Servicio<br><input type="text" class="column-filter" data-column="1"
                                style="width:100px"></th>
                        <th>Fecha Muestreo<br><input type="text" class="column-filter" data-column="2"
                                style="width:100px"></th>
                        <th>Fecha Recepcion<br><input type="text" class="column-filter" data-column="3"
                                style="width:100px"></th>
                        <th>Cliente<br><input type="text" class="column-filter" data-column="4" style="width:300px">
                        </th>
                        <th>Norma<br><input type="text" class="column-filter" data-column="5" style="width:100px"></th>
                        <th>Estado<br><input type="text" class="column-filter" data-column="6" style="width:70px"></th>
                        <th>Fecha creación<br><input type="text" class="column-filter" data-column="7"
                                style="width:100px"></th>
                        <th>Creado Por<br><input type="text" class="column-filter" data-column="8" style="width:100px">
                        </th>
                        <th>Fecha Actualizacion<br><input type="text" class="column-filter" data-column="9"
                                style="width:100px"></th>
                           
                    </tr>
                </thead>

                <tbody>
                    @foreach ($model2 as $item)
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

    @endsection

    @section('javascript')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="{{ asset('public/js/supervicion/cadena/cadena.js') }}?v=0.0.3"></script>
    @stop