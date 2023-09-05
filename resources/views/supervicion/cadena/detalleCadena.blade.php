@extends('voyager::master')

@section('content')

@section('page_header')

<h6 class="page-title">
    <i class="fa fa-truck-pickup"></i>
    Cadena de custodia
</h6>
@stop
<input type="text" id="idSol" value="{{$model->Id_solicitud}}" hidden>
<input type="text" id="idNorma" value="{{$model->Id_norma}}" hidden>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Solicitud ID: <strong>{{$model->Id_solicitud}}</strong></h6>
                            <h6>Servicio: <strong>{{$model->Servicio}}</strong></h6>
                            <h6>Tipo descarga: <strong>{{$model->Descarga}}</strong></h6>
                            <h6>Norma: <strong>{{$model->Clave}}</strong></h6>
                            <h6>Cliente: <strong>{{$model->Empresa_suc}}</strong></h6>
                            <h6>Intermediario: <strong>{{@$intermediario->Nombres}}</strong></h6>
                        </div>
                        <div class="col-md-4">
                            <h6>Fecha muestro: <strong>{{$model->Fecha_muestreo}}</strong></h6>
                            <h6>Estado: <strong>Reporte</strong></h6>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Supervisado</label>
                              <input class="form-check-input" id="ckSupervisado" type="checkbox" value="" id="defaultCheck1">
                            </div>
                            <div class="form-check">
                              <label class="form-check-label" for="defaultCheck1">Liberado</label>
                              <input class="form-check-input" id="ckLiberado" type="checkbox" value="" id="defaultCheck1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success" id="btnCadena">Imprimir</button>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>
    <div class="row">
        <div class=""></div>
        <div class="col-md-12">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <table id="tablePuntos" class="display compact cell-border " style="width:100%">
                                <thead>
                                    <tr>
                                     <th>Id</th>
                                     <th>Punto muestreo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($puntos as $item)
                                        <tr>
                                            <td>{{$item->Id_solicitud}}</td>
                                            @if ($swSir == true)
                                                <td>{{$item->Punto}}</td>
                                            @else
                                                <td>{{$item->Punto_muestreo}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                        </div>
                        <div class="col-md-6">
                           <div id="divTableParametros">
                            <table id="tableParametros" class="display compact cell-border" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Parametro</th>
                                        <th>Tipo formula</th>
                                        <th>Resultado</th>
                                        <th>Liberado</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>    
                                <tbody>
                        
                                </tbody>
                            </table>
                           </div>
                        </div>
                        <div class="col-md-3">
                            <button id="btnLiberar" class="btn-success"><i class="fas fa-square-root-alt"></i></button>
                            <button id="btnRegresar" class="btn-info"><i class="voyager-double-left"></i></button>
                            <div id="divTabDescripcion">
                                <table id="tableResultado" class="display compact cell-border" style="width:100%">
                                    <thead>
                                        <tr>                                     
                                         <th>Descripcion</th>
                                         <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            
                                    </tbody>
                                </table>
                            </div>
                            <table>
                                <tr>
                                    <td>Resultado</td>
                                    <td><input type="text" style="font-size: 20px;width: 100px;color:red;" id="resDes" value="0.0"></td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                </div>
              </div>
        
        </div> 
    </div>
</div>

@endsection
@section('javascript')
<script src="{{ asset('public/js/supervicion/cadena/detalleCadena.js') }}?v=1.0.16"></script>
@stop
