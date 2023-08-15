@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    Informes
    <i class="fas fa-chart-area"></i>
  </h6>
  @stop


  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="month" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <button class="btn btn-sm btn-success"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <!-- Primera sección de tablas -->
                        <div class="col-md-4">
                            <div style="width: 100%; overflow:scroll">
                                <table id="tableServicios" class="table" style="width: 100%; font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>Norma</th>
                                             <th>Tipo servicio</th>
                                            {{-- <th>Fecha impresion</th> --}}
                                            <th>Estado Reporte</th>
                                            <th>Causa reporte</th>
                                            <th>Usuario impresión</th>
                                            <th>Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($model as $item)
                                        <tr onclick="getPuntoMuestro({{$item->Id_solicitud}})">
                                            <td>{{$item->Id_solicitud}}</td>
                                            <td>{{$item->Folio_servicio}}</td>
                                            <td>{{$item->Empresa_suc}}</td>
                                            <td>{{$item->Clave_norma}}</td>
                                            <td>{{$item->Servicio}}</td>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$item->Observacion}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Segunda seccion de tablas -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="puntoMuestreo">Punto de muestreo</label>
                                        <div id="selPuntos">
                                            <select class="form-control" id="puntoMuestreo">
                                                <option value="">Puntos Muestreo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipoReporte">Tipo  de reporte</label>
                                        <select class="form-control" id="tipoReporte">
                                            <option value="2">Sin Comparación</option>
                                            <option value="1">Con comparación</option>
                                            <option value="4">Cadena de custodia</option>
                                            <option value="3">Campo</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2"> 
                                    <button class="btn btn-info" id="btnImprimir"><i class="voyager-cloud-download"></i> Descargar</button>
                                </div>
                            </div>

                            <div style="width: 100%; overflow:scroll">
                               <div id="divServicios">
                                <table id="tablaParametro" class="table" style="width: 100%; font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th>Norma</th>
                                            <th>Parametro</th>
                                            <th>Unidad</th>
                                            <th>Resultado</th>
                                            <th>Concentración</th>
                                            <th>Diagnostico</th>
                                            <th>Liberado</th>
                                            <th>#</th>
                                            <th># Muestra</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
    </div>
  </div>
@endsection  

@section('javascript')
    <script src="{{asset('/public/js/informes/informes.js')}}?v=1.0.1"></script>
@stop