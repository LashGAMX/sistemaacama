@extends('voyager::master')
@section('page_header')
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <h3>En Laboratorio: {{$model->count()}}</h3>
                </div>
                <div class="col-md-3">
                    <h3>Total en lab: {{$subModel->count()}}</h3>
                </div>
                <div class="col-md-2">
                    <span>Adelantados: {{@$diasFolio[6]}}</span>
                    <br>
                    <span>0 Dias: {{$diasFolio[0]}}</span>
                    <br>
                    <span>1 Dia: {{$diasFolio[1]}}</span>
                    <br>
                    <span>2 Dia: {{$diasFolio[2]}}</span>
                </div>
                <div class="col-md-2">
                    <span>3 Dia: {{$diasFolio[3]}}</span>
                    <br>
                    <span>4 Dia: {{$diasFolio[4]}}</span>
                    <br>
                    <span>> 5 Dia: {{$diasFolio[5]}}</span>
                </div>
                <div class="col-md-2">
                    <h4>Total al dia: {{$diasFolio[0] + $diasFolio[1] + $diasFolio[2] + $diasFolio[3] + $diasFolio[4] + $diasFolio[5]}}</h4>
                    <h4>Total Siralab: {{$siralab->count()}}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table" id="kpiTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Folio</th>
                        <th>Norma</th>
                        <th>Empresa</th>
                        <th>Recepcion</th>
                        <th>Salida?</th>
                        <th>Observacion</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $num = 1;
                    @endphp
                    @foreach ($model as $item)
                        <tr>
                            <td>{{$num++}}</td>
                            <td>{{$item->Folio}}</td>
                            <td>{{$item->Clave_norma}}</td>
                            <td>{{$item->Empresa}}</td>
                            <td>{{$item->Hora_recepcion}}</td>
                            <td>
                             @switch($item->Id_norma)
                                        @case(1)
                                        @case(27)  
                                        {{\Carbon\Carbon::parse(@$item->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                                            @break
                                        @case(5)
                                        @case(30)  
                                        {{\Carbon\Carbon::parse(@$item->Hora_recepcion)->addDays(14)->format('d/m/Y')}}
                                            @break
                                        @default
                                        {{\Carbon\Carbon::parse(@$item->Hora_recepcion)->addDays(11)->format('d/m/Y')}}
                                    @endswitch
                            </td>
                            <td>{{$item->Obs_proceso}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/kpi/laboratorio.js') }}?v=0.0.1"></script>
@stop