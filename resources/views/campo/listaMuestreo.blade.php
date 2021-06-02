@extends('voyager::master')

@section('content')


@section('page_header')
<h6 class="page-title"> 
  <i class="voyager-treasure"></i>
  Lista muestreo
</h6>
@stop
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="text" placeholder="Nombre cliente" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="text" placeholder="Folio" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <select type="Mes" class="form-control" placeholder="">
                                <option>mes</option>
                                <option>Enero</option>
                                <option>Febrero</option>
                                <option>Marzo</option>
                                <option>Abril</option>
                                <option>Mayo</option>
                                <option>Junio</option>
                                <option>Julio</option>
                                <option>Agosto</option>
                                <option>Septiembre</option>
                                <option>Octubre</option>
                                <option>Noviembre</option>
                                <option>Diciembre</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="text" placeholder="Año" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn btn-success"><i class="fa fa-search"></i>Buscar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <button id="btnCapturar" class="btn btn-primary"><i class="fa fa-file-invoice"></i> Capturar</button>
                    </div>
                    <div class="col-md-1">
                        <button id="btnHojaCampo" class="btn btn-secundary"><i class="fa fa-print"></i> Hoja campo</button>
                    </div>
                    <div class="col-md-1">
                        <button id="btnBitacora" class="btn btn-secundary"><i class="fa fa-print"></i> Bitacora</button>
                    </div>
                </div>
                
                <table class="table table-sm" id="listaServicio">
                    <thead>
                        <tr>
                            <th>Id solicitud</th>
                            <th>Solicitud</th>
                            <th>Cliente</th>
                            <th>PM</th>
                            <th>Fecha muestreo</th>
                            <th>Estado</th>
                            <th>Muestreador</th>
                            <th>Captura</th>
                            <th>Equipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Serie</th>
                            <th>Creao</th>
                            <th>Fecha creación</th>
                            <th>Modifico</th>
                            <th>Fecha modificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12</td>
                            <td>127-12/21</td>
                            <td>Luis</td>
                            <td>PUNTO DE MUESTREO FINAL</td>
                            <td>02/06/2021</td>
                            <td>Puebla</td>
                            <td>Ing Muestreo</td>
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
@endsection  


@section('javascript')
    <script src="{{asset('js/campo/listaMuestreo.js')}}"></script>
    <script src="{{asset('js/libs/componentes.js')}}"></script>
    <script src="{{asset('js/libs/tablas.js')}}"></script>
@stop

