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
                                {{-- <button class="btn btn-secondary" id="btnGenControl" onclick="imprimir();">Imprimir</button> --}}
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
                {{-- <div class="col-md-1">
                    <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                </div> --}}
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                        id="btnGenControlInd">Generar control</button>
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
                            {{-- <th># Toma</th> --}}
                            <th>Norma</th>
                            <th>Resultado</th>
                            <th>Observacion</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
      </div>

        <!-- Modal -->
        <div class="modal fade" id="modalDqo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="">Captura de resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Observación</label>
                                    <input type="text" class="form-control" id="observacionDqo"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" onclick="updateObsVolumetria(2,'observacionDqo')"
                                        id=""><i class="voyager-check"></i> Aplicar</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="btnGuardarDqo"><i class="voyager-upload"></i>
                                    Guardar</button>&nbsp;&nbsp;
                            </div>
                            <div class="col-md-2"> 
                                <button class="btn btn-primary" id="btnEjecutarDqo"><i class="voyager-play"></i>
                                    Ejecutar</button>
                            </div>
                            {{-- <div class="col-md-2">
                                <button class="btn btn-warning">Liberar</button>
                            </div> --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" id="resultadoDqo" style="font-size: 20px;color:red;"
                                        placeholder="Resultado">
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id=""> 
                                <thead>
                                  <tr>
                                    <th>Parametro</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                    <th>Valor2</th>
                                  </tr>
                                </thead>
                                <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                <tbody>
                                    <tr>
                                        <td>B</td>
                                        <td>Mililitros Titulados Muestra</td>
                                        <td><input type="text" id="tituladoDqo1" value="0"></td>
                                        <td><input type="text" id="tituladoDqo2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Molaridad del FAS</td>
                                        <td><input type="text" id="MolaridadDqo1" value="0"></td>
                                        <td><input type="text" id="MolaridadDqo2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>CA</td>
                                        <td>Mililitros titulados del blanco</td>
                                        <td><input type="text" id="blancoDqo1" value="0"></td>
                                        <td><input type="text" id="blancoDqo2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>Factor de quivalencia</td>
                                        <td><input type="text" id="factorDqo1" value="0"></td>
                                        <td><input type="text" id="factorDqo2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>Volumen de muestra</td>
                                        <td><input type="text" id="volDqo1" value="0"></td>
                                        <td><input type="text" id="volDqo2" value="0"></td>
                                    </tr>
                                    
                                
                                </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                   
                  </div>
                </div>
              </div>
              
             </div>

              <!-- Modal -->
        <div class="modal fade" id="modalNitrogeno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                
                  <div class="modal-header">
                    <h5 class="modal-title" id="">Captura de resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Observación</label>
                                    <input type="text" class="form-control" id="observacionNitro"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" onclick="updateObsVolumetria(3,'observacionNitro')"
                                        id=""><i class="voyager-check"></i> Aplicar</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="btnGuardarNitro"><i class="voyager-upload"></i>
                                    Guardar</button>&nbsp;&nbsp;
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="btnEjecutarNitro"><i class="voyager-play"></i>
                                    Ejecutar</button>
                            </div>
                            {{-- <div class="col-md-2">
                                <button class="btn btn-warning">Liberar</button>
                            </div> --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" id="resultadoNitro" style="font-size: 20px;color:red;"
                                        placeholder="Resultado">
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id=""> 
                                <thead>
                                  <tr>
                                    <th>Parametro</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                    <th>Valor2</th>
                                  </tr>
                                </thead>
                                <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                <tbody>
                                    <tr>
                                        <td>A</td>
                                        <td>Mililitros Titulados Muestra</td>
                                        <td><input type="text" id="tituladosNitro1" value="0"></td>
                                        <td><input type="text" id="tituladosNitro2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Mililitros titulados del blanco</td>
                                        <td><input type="text" id="blancoNitro1" value="0"></td>
                                        <td><input type="text" id="blancoNitro2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>Molaridad del FAS</td>
                                        <td><input type="text" id="molaridadNitro1" value="0"></td>
                                        <td><input type="text" id="molaridadNitro2" value="0"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>D</td>
                                        <td>Factor de quivalencia</td>
                                        <td><input type="text" id="factorNitro1" value="0"></td>
                                        <td><input type="text" id="factorNitro2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>Factor de conversión</td>
                                        <td><input type="text" id="conversion1" value="0"></td>
                                        <td><input type="text" id="conversion2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>Volumen de muestra</td>
                                        <td><input type="text" id="volNitro1" value="0"></td>
                                        <td><input type="text" id="volNitro2" value="0"></td>
                                    </tr>
                                    
                                
                                </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
              
             </div>


             
        <!-- Modal Control Calidad-->
        <div class="modal fade" id="modalCalidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="">Control de calidad</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Formula</label>
                                        <input type="text" id="mFormula" disabled value="Forms">
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Tipo</label>
                                        <select class="form-control" id="controlCalidad">
                                            @foreach ($controlModel as $item)
                                            <option value="{{$item->Id_control}}">{{$item->Control}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" onclick="createControlCalidad()"
                                class="btn btn-primary">Generar</button>
                        </div>
                </div>
            </div>
        </div>
            
               <!-- Modal -->
        <div class="modal fade" id="modalCloro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="">Captura de resultados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Observación</label>
                                    <input type="text" class="form-control" id="observacionCloro"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" onclick="updateObsVolumetria(1,'observacionCloro')"
                                        id="btnAplicarObsCloro"><i class="voyager-check"></i> Aplicar</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="btnGuardarCloro"><i class="voyager-upload"></i>
                                    Guardar</button>&nbsp;&nbsp;
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="btnEjecutarCloro"><i class="voyager-play"></i>
                                    Ejecutar</button>
                            </div>
                            {{-- <div class="col-md-2">
                                <button class="btn btn-warning">Liberar</button>
                            </div> --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" id="resultadoCloro" style="font-size: 20px;color:red;"
                                        placeholder="Resultado">
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" id=""> 
                                <thead>
                                  <tr>
                                    <th>Parametro</th>
                                    <th>Descripción</th>
                                    <th>Valor</th>
                                    <th>Valor2</th>
                                  </tr>
                                </thead>
                                <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                <tbody>
                                    <tr>
                                        <td>A</td>
                                        <td>MILILITROS TITULADOS DE MUESTRA</td>
                                        <td><input type="text" id="cloroA1" value="0"></td>
                                        <td><input type="text" id="cloroA2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>mL DE MUESTRA</td>
                                        <td><input type="text" id="cloroE1" value="0"></td>
                                        <td><input type="text" id="cloroE2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>H</td>
                                        <td>pH FINAL</td>
                                        <td><input type="text" id="cloroH1" value="0"></td>
                                        <td><input type="text" id="cloroH2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>pH Inicial</td>
                                        <td><input type="text" id="cloroG1" value="0"></td>
                                        <td><input type="text" id="cloroG2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>BLANCO DE ANALISIS</td>
                                        <td><input type="text" id="cloroB1" value="0"></td>
                                        <td><input type="text" id="cloroB2" value="0"></td>
                                    </tr>
                                    <tr> 
                                        <td>C</td>
                                        <td>NORMALIDAD REAL</td>
                                        <td><input type="text" id="cloroC1" value="0"></td>
                                        <td><input type="text" id="cloroC2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>FACTOR DE CONVERSION mg/L</td>
                                        <td><input type="text" id="cloroD1" value="35450"></td>
                                        <td><input type="text" id="cloroD2" value="35450"></td>
                                    </tr>
                                    
                                </tbody>
                              </table>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
              
             </div>
</div>
  @stop

  @section('javascript')
  <script src="{{asset('/public/js/laboratorio/fq/capturaVolumetria.js')}}"></script>
  @stop

@endsection


