@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados
  </h6>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                        <option value= {{$parametros->Id_parametro}}>{{$parametros->Parametro}}</option>
                    @endforeach
                  </select>
            </div>
        </div>
        {{-- <div class="col-md-3"> 
            <div class="form-group">
                <label for="">Núm. muestra</label>
                <input type="text" style="width: " class="form-control" id="numeroMuestra">
            </div>
        </div> --}}
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control" id="fechaAnalisis">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" onclick="getDataCaptura()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
    <input  id="idLote" hidden>   
</div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div id="divLote">
                        <table class="table" id=""> 
                            <thead>
                              <tr>
                                <th>Folio</th>
                                <th>Fecha lote</th>
                                <th>Total asignados</th>
                                <th>Total liberados</th>
                                <th>Opc</th>
                              </tr>
                            </thead>
                            <!-- <button class="btn btn-success" id="btnImprimir"><i class="fas fa-file-download"></i></button> -->
                          </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                            <div id="infoGlobal">
                                <button class="btn btn-secondary" id="btnGenControl" onclick="imprimir();">Imprimir</button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <p class="">Información</p>
                            <div id="infoGen">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                </div>
                <div class="col-md-1">
                    {{-- <button class="btn btn-secondary">Liberar todo</button> --}}
                </div>
                <div class="col-md-1">
                    {{-- <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                          Blanco
                        </label>
                      </div> --}}
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" id="btnGenControl" onclick="generarControles();">Generar controles</button>
                </div>

                <div class="col-md-1">
                    {{-- <button class="btn btn-secondary">Duplicar</button> --}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="divTablaControles">
                <thead>
                    <tr>
                        <th>Opc</th>
                        <th>Folio</th>
                        <th># Toma</th>
                        <th>Norma</th>
                        <th>Resultado</th>
                        <th>Tipo Analisis</th>
                        <th>Observacion</th>    
                    </tr>
                </thead>
            </div>
        </div>
      </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCaptura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Captura de resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                        <div class="col-md-2">
                            <button class="btn btn-success" id="guardar">Guardar</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" id="ejecutarModal">Ejecutar</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info">Liberar</button>
                        </div>
                    </div>
                  </div>
                  <div class="modal-body"> 
                      <div class="row">
                         <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Parametro</th>
                                        <th>Descripción</th>
                                        <th>Valor</th>
                                        <th>Valor2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>ABS</td>
                                        <td>ABS Promedio</td>
                                        <td><input type="text" id="abs1"></td>
                                        <td><input type="text" id="abs2"></td>
                                    </tr>
                                    <tr>
                                        <td>CA</td>
                                        <td>Blanco</td>
                                        <td><input type="text" id="blanco1"></td>
                                        <td><input type="text" id="blanco2"></td>
                                    </tr>
                                    <tr>
                                        <td>CB</td>
                                        <td>b</td>
                                        <td><input type="text" id="b1"></td>
                                        <td><input type="text" id="b2"></td>
                                    </tr>
                                    <tr>
                                        <td>CM</td>
                                        <td>m</td>
                                        <td><input type="text" id="m1"></td>
                                        <td><input type="text" id="m2"></td>
                                    </tr>
                                    <tr>
                                        <td>CR</td>
                                        <td>r</td>
                                        <td><input type="text" id="r1"></td>
                                        <td><input type="text" id="r2"></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Factor dilucion</td>
                                        <td><input type="text" id="fDilucion1"></td>
                                        <td><input type="text" id="fDilucion2"></td>
                                    </tr>

                                    <tr>
                                        <td>E</td>
                                        <td>Vol de la muestra</td>
                                        <td><input type="text" id="volMuestra1"></td>
                                        <td><input type="text" id="volMuestra2"></td>
                                    </tr>
                                    <tr>
                                        <td>X</td>
                                        <td>Absorbancia1</td>
                                        <td><input type="text" id="abs11"></td>
                                        <td><input type="text" id="abs12"></td>
                                    </tr>
                                    <tr>
                                        <td>Y</td>
                                        <td>Absorbancia2</td>
                                        <td><input type="text" id="abs21"></td>
                                        <td><input type="text" id="abs22"></td>
                                    </tr>
                                    <tr>
                                        <td>Z</td>
                                        <td>Absorbancia3</td>
                                        <td><input type="text" id="abs31"></td>
                                        <td><input type="text" id="abs32"></td>
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
  @stop

  @section('javascript')
  <script src="{{asset('/public/js/laboratorio/fq/capturaEspectro.js')}}"></script>
  <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
  <script src="{{asset('/public/js/libs/tablas.js')}}"></script>
  @stop

@endsection  


