@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    Informes
    <i class="fas fa-chart-area"></i>
  </h6>
  @stop

<style>
#informeTable tbody tr {
    cursor: pointer;
    color: #000;
}

#informeTable tbody tr.selected {
    background-color: #6BBFA7; /* azul cielo */
    color: #000; /* puedes mantener texto negro o blanco según tu preferencia */
}
</style>

  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <input type="month" class="form-control form-control-sm">
                    </div>
                </div> -->
                <!-- <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-sm btn-success"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nota">Firma Aut</label>
                        <input type="checkbox" id="firmaAut">
                        
                    </div>
                </div> -->
                <div class="col-md-12">
                    <div class="row">
                        <!-- Primera sección de tablas -->
                        <div class="col-md-4">
                          <div style="width: 100%; overflow-x: auto; max-height: 400px; overflow-y: auto;">
                            <table id="informeTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr class="filters">
                                        <th><input type="text" class="form-control" style="width:10px;" placeholder="Id"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="Folio"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="Empresa"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="Norma"></th>
                                        <th><input type="text" class="form-control form-control-sm" placeholder="Servicio"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                                            <option value="1">Informe general</option>
                                            
                                            
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
                                            <th>Límite Pd</th>
                                            <!-- Iniciales de quien lo analizó -->
                                        </tr>
                                    </thead>
                                    <tbody id="datosTablaParametro">
                                        <!-- <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr> -->
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
    <script src="{{asset('/public/js/alimentos/informes.js')}}?v=1.0.1"></script>
@stop