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
                    @foreach ($parametro as $item)
                        <option value= {{$item->Id_parametro}}>{{$item->Parametro}} ({{$item->Tipo_formula}})</option>
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
                {{-- <div class="col-md-2">
                    <button class="btn btn-secondary" id="btnGenControl" onclick="generarControles();">Generar controles</button>
                </div> --}}

                <div class="col-md-1">
                    {{-- <button class="btn btn-secondary">Duplicar</button> --}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="divTablaControles">
               <table class="table">
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
               </table>
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
                        <div>
                        <input type="text" id="resultado">
                        </div>
                    </div>
                  </div>
                  <div class="modal-body" id="prueba"> 
                      <input type="text" id="idMuestra" hidden>
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
                                        <td><input name="campos" type="text" id="blanco1"></td>
                                        <td><input name="campos" type="text" id="blanco2"></td>
                                    </tr>
                                    <tr>
                                        <td>CB</td>
                                        <td>b</td>
                                        <td><input name="campos" type="text" id="b1"></td>
                                        <td><input name="campos" type="text" id="b2"></td>
                                    </tr>
                                    <tr>
                                        <td>CM</td>
                                        <td>m</td>
                                        <td><input name="campos" type="text" id="m1"></td>
                                        <td><input name="campos" type="text" id="m2"></td>
                                    </tr>
                                    <tr>
                                        <td>CR</td>
                                        <td>r</td>
                                        <td><input name="campos" type="text" id="r1"></td>
                                        <td><input name="campos" type="text" id="r2"></td>
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
                                        <td><input name="campos" type="text" id="volMuestra1"></td>
                                        <td><input name="campos" type="text" id="volMuestra2"></td>
                                    </tr>
                                    <tr>
                                        <td>X</td>
                                        <td>Absorbancia1</td>
                                        <td><input name="campos" type="text" id="abs11"></td>
                                        <td><input name="campos" type="text" id="abs12"></td>
                                    </tr>
                                    <tr>
                                        <td>Y</td>
                                        <td>Absorbancia2</td>
                                        <td><input name="campos" type="text" id="abs21"></td>
                                        <td><input name="campos" type="text" id="abs22"></td>
                                    </tr>
                                    <tr>
                                        <td>Z</td>
                                        <td>Absorbancia3</td>
                                        <td><input name="campos" type="text" id="abs31"></td>
                                        <td><input name="campos" type="text" id="abs32"></td>
                                    </tr>
                                </tbody>
                            </table>
                         </div>
                      </div>
                  </div>
                </div>
              </div>
              
             </div>


                <!-- Modal -->
        <div class="modal fade" id="modalCapturaSulfatos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <button class="btn btn-primary" id="ejecutarModalSulfato">Ejecutar</button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info">Liberar</button>
                        </div>
                        <div>
                        <input type="text" id="resultadoF">
                        </div>
                    </div>
                  </div>
                  <div class="modal-body" id="prueba"> 
                      <input type="text" id="idMuestra" hidden>
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
                                        <td><input type="text" id="abs1F"></td>
                                        <td><input type="text" id="abs2F"></td>
                                    </tr>
                                    <tr>
                                        <td>CA</td>
                                        <td>Blanco</td>
                                        <td><input name="campos" type="text" id="blanco1F"></td>
                                        <td><input name="campos" type="text" id="blanco2F"></td>
                                    </tr>
                                    <tr>
                                        <td>CB</td>
                                        <td>b</td>
                                        <td><input name="campos" type="text" id="b1F"></td>
                                        <td><input name="campos" type="text" id="b2F"></td>
                                    </tr>
                                    <tr>
                                        <td>CM</td>
                                        <td>m</td>
                                        <td><input name="campos" type="text" id="m1F"></td>
                                        <td><input name="campos" type="text" id="m2F"></td>
                                    </tr>
                                    <tr>
                                        <td>CR</td>
                                        <td>r</td>
                                        <td><input name="campos" type="text" id="r1F"></td>
                                        <td><input name="campos" type="text" id="r2F"></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Factor dilucion</td>
                                        <td><input type="text" id="fDilucion1F"></td>
                                        <td><input type="text" id="fDilucion2F"></td>
                                    </tr>

                                    <tr>
                                        <td>E</td>
                                        <td>Vol de la muestra</td>
                                        <td><input name="campos" type="text" id="volMuestra1F"></td>
                                        <td><input name="campos" type="text" id="volMuestra2F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS1</td>
                                        <td>Absorbancia1</td>
                                        <td><input name="campos" type="text" id="abs11F"></td>
                                        <td><input name="campos" type="text" id="abs12F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS2</td>
                                        <td>Absorbancia2</td>
                                        <td><input name="campos" type="text" id="abs21F"></td>
                                        <td><input name="campos" type="text" id="abs22F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS3</td>
                                        <td>Absorbancia3</td>
                                        <td><input name="campos" type="text" id="abs31F"></td>
                                        <td><input name="campos" type="text" id="abs32F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS4</td>
                                        <td>Absorbancia4</td>
                                        <td><input name="campos" type="text" id="abs41F"></td>
                                        <td><input name="campos" type="text" id="abs42F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS5</td>
                                        <td>Absorbancia5 </td>
                                        <td><input name="campos" type="text" id="abs51F"></td>
                                        <td><input name="campos" type="text" id="abs52F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS6</td>
                                        <td>Absorbancia6</td>
                                        <td><input name="campos" type="text" id="abs61F"></td>
                                        <td><input name="campos" type="text" id="abs62F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS7</td>
                                        <td>Absorbancia7</td>
                                        <td><input name="campos" type="text" id="abs71F"></td>
                                        <td><input name="campos" type="text" id="abs72F"></td>
                                    </tr>
                                    <tr>
                                        <td>ABS8</td>
                                        <td>Absorbancia8</td>
                                        <td><input name="campos" type="text" id="abs81F"></td>
                                        <td><input name="campos" type="text" id="abs82F"></td>
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
  <script src="{{asset('/public/js/libs/funciones.js')}}"></script>
  @stop

@endsection  


