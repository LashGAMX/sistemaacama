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
                                            <th>Fecha impresion</th>
                                            <th>Estado Reporte</th>
                                            <th>Causa reporte</th>
                                            <th>Usuario impresión</th>
                                            <th>Descripción</th>
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
                                            <td></td>
                                        </tr>
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
                                        <select class="form-control" id="puntoMuestreo">
                                            <option value="">Puntos Muestreo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipoReporte">Tipo  de reporte</label>
                                        <select class="form-control" id="tipoReporte">
                                            @foreach ($tipoReporte as $item)
                                                <option value="{{$item->Id_tipo_reporte}}">{{$item->Tipo}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div style="width: 100%; overflow:scroll">
                                <table id="tableServicios" class="table" style="width: 100%; font-size: 10px">
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
@endsection  

@section('javascript')
    <script src="{{asset('/public/js/informes/informes.js')}}"></script>
@stop