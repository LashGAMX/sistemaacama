@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados Micro
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
            <button class="btn btn-success" onclick="getLoteMicro()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
    <input  id="idLote" hidden>   
</div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
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
                            <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                          </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                            <div id="infoGlobal">

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
                <table class="table" id="tablaControles">
                    <thead>
                        <tr>
                            <th>Opc</th>
                            <th>Folio</th>
                            <th># Toma</th>
                            <th>Norma</th>
                            <th>Resultado</th>
                            <th>Tipo Analisis</th>
                            {{-- <th>Observacion</th>     --}}
                        </tr>
                    </thead>
                    
                    {{-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> --}}
                </table> 
            </div>
        </div>
      </div>
        <!-- Modal -->
        <div  class="modal fade" id="modalCapturaCol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                 <form wire:submit.prevent="create">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Captura coliformes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
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
                                        <td>D1</td>
                                        <td>Dilucion 1</td>
                                        <td><input type="text" id="dil1" value="0"></td>
                                        <td><input type="text" id="dil12" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>D2</td>
                                        <td>Dilucion 2</td>
                                        <td><input type="text" id="dil2" value="0"></td>
                                        <td><input type="text" id="dil22" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>D3</td>
                                        <td>Dilucion 3</td>
                                        <td><input type="text" id="dil3" value="0"></td>
                                        <td><input type="text" id="dil32" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>NMP</td>
                                        <td>Indice NMP</td>
                                        <td><input type="text" id="nmp1" value="0"></td>
                                        <td><input type="text" id="nmp2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>G3</td>
                                        <td>mL De muestra en todos los tubos</td>
                                        <td><input type="text" id="" value="0"></td>
                                        <td><input type="text" id="todos2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>G2</td>
                                        <td>mL De muestra en tubos negativos</td>
                                        <td><input type="text" id="negativos1" value="0"></td>
                                        <td><input type="text" id="negativos2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>G1</td>
                                        <td># de tubos positivos</td>
                                        <td><input type="text" id="positivo1" value="0"></td>
                                        <td><input type="text" id="positivo2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C1</td>
                                        <td>Prueba confirmativa 1</td>
                                        <td><input type="text" id="con1" value="0"></td>
                                        <td><input type="text" id="con12" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C2</td>
                                        <td>Prueba confirmativa 2</td>
                                        <td><input type="text" id="con2" value="0"></td>
                                        <td><input type="text" id="con22" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C3</td>
                                        <td>Prueba confirmativa 3</td>
                                        <td><input type="text" id="con3" value="0"></td>
                                        <td><input type="text" id="con32" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C4</td>
                                        <td>Prueba confirmativa 4</td>
                                        <td><input type="text" id="con4" value="0"></td>
                                        <td><input type="text" id="con42" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C5</td>
                                        <td>Prueba confirmativa 5</td>
                                        <td><input type="text" id="con5" value="0"></td>
                                        <td><input type="text" id="con52" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C6</td>
                                        <td>Prueba confirmativa 6</td>
                                        <td><input type="text" id="con6" value="0"></td>
                                        <td><input type="text" id="con62" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C7</td>
                                        <td>Prueba confirmativa 7</td>
                                        <td><input type="text" id="con7" value="0"></td>
                                        <td><input type="text" id="con72" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C8</td>
                                        <td>Prueba confirmativa 8</td>
                                        <td><input type="text" id="con8" value="0"></td>
                                        <td><input type="text" id="con82" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C9</td>
                                        <td>Prueba confirmativa 9</td>
                                        <td><input type="text" id="con9" value="0"></td>
                                        <td><input type="text" id="con92" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P1</td>
                                        <td>Prueba Presuntiva 1</td>
                                        <td><input type="text" id="pre1" value="0"></td>
                                        <td><input type="text" id="pre12" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P2</td>
                                        <td>Prueba Presuntiva 2</td>
                                        <td><input type="text" id="pre2" value="0"></td>
                                        <td><input type="text" id="pre22" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P3</td>
                                        <td>Prueba Presuntiva 3</td>
                                        <td><input type="text" id="pre3" value="0"></td>
                                        <td><input type="text" id="pre32" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P4</td>
                                        <td>Prueba Presuntiva 4</td>
                                        <td><input type="text" id="pre4" value="0"></td>
                                        <td><input type="text" id="pre42" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P5</td>
                                        <td>Prueba Presuntiva 5</td>
                                        <td><input type="text" id="pre5" value="0"></td>
                                        <td><input type="text" id="pre52" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P6</td>
                                        <td>Prueba Presuntiva 6</td>
                                        <td><input type="text" id="pre6" value="0"></td>
                                        <td><input type="text" id="pre62" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P7</td>
                                        <td>Prueba Presuntiva 7</td>
                                        <td><input type="text" id="pre7" value="0"></td>
                                        <td><input type="text" id="pre72" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P8</td>
                                        <td>Prueba Presuntiva 8</td>
                                        <td><input type="text" id="pre8" value="0"></td>
                                        <td><input type="text" id="pre82" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>P9</td>
                                        <td>Prueba Presuntiva 9</td>
                                        <td><input type="text" id="pre9" value="0"></td>
                                        <td><input type="text" id="pre92" value="0"></td>
                                    </tr>
                                </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
                </div>
              </div>
              
             </div>
        </div>

           <!-- Modal -->
           <div  class="modal fade" id="modalCapturaHH" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                 <form wire:submit.prevent="create">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Captura HH</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
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
                                        <td>A. lumbicoides</td>
                                        <td><input type="text" id="lum1" value="0"></td>
                                        <td><input type="text" id="lum2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>C</td>
                                        <td>H. NANA</td>
                                        <td><input type="text" id="na1" value="0"></td>
                                        <td><input type="text" id="na2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>E</td>
                                        <td>TAENIA SP</td>
                                        <td><input type="text" id="sp1" value="0"></td>
                                        <td><input type="text" id="sp2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>D</td>
                                        <td>T. Trichiura</td>
                                        <td><input type="text" id="tri1" value="0"></td>
                                        <td><input type="text" id="tri2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>B</td>
                                        <td>Unicinarias</td>
                                        <td><input type="text" id="uni1" value="0"></td>
                                        <td><input type="text" id="uni2" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td>G</td>
                                        <td>Vol. Muestra</td>
                                        <td><input type="text" id="volH1" value="0"></td>
                                        <td><input type="text" id="volH" value="0"></td>
                                    </tr>
                                </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
                </div>
              </div>
              
             </div>
        </div>


  @stop

  @section('javascript')
  <script src="{{asset('/public/js/laboratorio/mb/captura.js')}}"></script>
  @stop

@endsection  


