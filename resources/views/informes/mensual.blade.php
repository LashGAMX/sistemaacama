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
                                            <th>Cliente</th>
                                            <th>Norma</th>
                                            <th>Muestreo</th>
                                            <th>Punto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                               
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Segunda seccion de tablas -->
                        <div class="col-md-8">

                            <div style="width: 100%; overflow:scroll">
                               <div id="divServicios">
                                <table id="tablaParametro" class="table" style="width: 100%; font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th>Fórmula</th>
                                            <th>Unidad</th>
                                            <th>Método prueba</th>
                                            <th>Prom diario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
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
    {{-- <script src="{{asset('/public/js/informes/informes.js')}}"></script> --}}
@stop