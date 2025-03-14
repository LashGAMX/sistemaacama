@extends('voyager::master')
@section('page_header')
@stop
@section('content') 
<div class="container-fluid">
    <div class="row">
       <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                    <button id="btnPagar" class="btn btn-success">Pagado</button>
                </div>
                <div class="col-md-2">
                    <button id="btnCredito" class="btn btn-warning">Credito</button>
                </div>
                <div class="col-md-2">
                    <button id="btnRetenido" class="btn btn-danger">Retenido</button>
                </div>
                <div class="col-md-2">
                    <button id="getDescargar" class="btn btn-info">Descargar</button>
                </div>
            </div>
       </div>
       <div class="col-md-12">
            <table id="tablaServicio" class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Folio</th>
                        <th>Empresa</th>
                        <th>Norma</th>
                        <th>Pass</th>
                        <th>Fec. Muest.</th>
                        <th>Servicio</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $aux = "";
                    @endphp
                    @foreach ($model as $item)
                            @switch($item->Pagado)
                                @case(1)
                                    @php
                                        $aux = "bg-success";
                                    @endphp
                                    @break
                                @case(2)
                                    @php
                                        $aux = "bg-warning";
                                    @endphp
                                    @break
                                @case(3)
                                    @php
                                        $aux = "bg-danger";
                                    @endphp
                                    @break
                                @default
                                    @php
                                        $aux = "";    
                                    @endphp
                                    @break
                            @endswitch
                            
                        <tr>
                            <td class="{{$aux}}">{{$item->Id_solicitud}}</td>
                            <td class="{{$aux}}">{{$item->Folio}}</td>
                            <td class="{{$aux}}">{{$item->Empresa}}</td>
                            <td class="{{$aux}}">{{$item->Clave_norma}}</td>
                            <td class="{{$aux}}">{{$item->Pass_archivo}}</td>
                            <td class="{{$aux}}">{{$item->Fecha_muestreo}}</td>
                            <td class="{{$aux}}">{{$item->Servicio}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
       </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/cobranza/servicios.js') }}?v=0.0.1"></script>
@stop