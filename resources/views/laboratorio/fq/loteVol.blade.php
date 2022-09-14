@extends('voyager::master')

@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-data"></i>    
    Lote
  </h6>
 
<div class="container-fluid">
  <div class="row"> 
    <div class="col-md-12">
      <div class="row">   
         
        <div class="col-md-3">
          <div class="form-group">
            <label for="exampleFormControSelect1">Tipo fórmula</label>
            <select class="form-control" id="tipoFormula">
              @foreach($parametro as $item)
                <option value="{{$item->Id_parametro}}">{{$item->Parametro}} ({{$item->Tipo_formula}})</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="">Fecha lote</label>
            <input type="date" id="fecha" class="form-control" placeholder="Fecha lote">
          </div>
        </div>
        
        <div class="col-md-2">
          <button class="btn btn-success" onclick="buscarLote()">Buscar</button>
        </div>        
        <div class="col-md-4">
          <h1>Datos cloruros</h1>
        </div>
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="row"> 
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalCrearLote" class="btn btn-info">Crear lote</button>
        </div>
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalProbar" id="btnDatosLote" class="btn btn-info">Datos lote</button>
        </div>
        
      </div>
      <div class="" id="divTable">
        
      <table class="table" id="tableObservacion"> 
        <thead>
          <tr>
            <th>#</th>
            <th>Tipo formula</th>
            <th>Fecha lote</th>
            <th>Fecha creacion</th>
            <th>Opc</th>
          </tr>
    
        </thead>
        <tbody>
                  
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalCrearLote" tabindex="-1" role="dialog" aria-labelledby="modalCrearLote" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear lote</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            
            <div class="form-group">
              <label for="exampleFormControSelect1">Tipo fórmula</label>
              <select class="form-control" id="tipoFormula">
                @foreach($parametro as $item)
                  <option value="{{$item->Id_parametro}}"> {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
              </select>
            </div>
     
            <div class="col-md-12">
              <div class="form-group">
                <input type="date" id="fechaLote" class="form-control">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnCreateLote" onclick="createLote()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>


 <!-- Modal -->
 <div class="modal fade" id="modalProbar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 90%">
    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        
        <label>ID Lote: <input type="text" id="idLoteHeader" size=10 /> <button class="btn btn-info" onclick="getDatalote();">Buscar</button></label>
        <label>Fecha Lote: <input type="datetime-local" id="fechaLote"/></label>                
    
        <button type="button" class="btn btn-success" id="guardarTodo">Guardar</button>        
        <div id="btnRefresh"></div>        
        <ul class="nav nav-tabs" id="myTab" role="tablist"> 
          
          <li class="nav-item" role="menu">
            <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true" onclick=''>Fórmulas Globales</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="dqo-tab" data-toggle="tab" href="#dqo" role="tab" aria-controls="dqo" aria-selected="false">DQO</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="procedimiento-tab" data-toggle="tab" href="#procedimiento" role="tab" aria-controls="procedimiento" aria-selected="false" onclick='isSelectedProcedimiento("procedimiento-tab");'>Procedimiento/Validación</a>
          </li>          
        </ul>
      </div>

      <div class="modal-body" id="modal">
        <div class="row">
          <div class="col-md-12">            
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade  active" id="formulaGlobal" role="tabpanel" aria-labelledby="formulaGlobal-tab">    
                <div class="row">
                  <div class="col-md-12">
                    <button id="btnGuardarVal" class="btn btn-success">Guardar</button>
                    <button id="btnEjecutarVal" class="btn btn-success">Ejcutar</button>
                    <button id="btnLimpiarVal" class="btn btn-success">Limpiar</button>
                  </div>
                </div>
                      <!-- ***************************** --> 
                <!-- Inicio Cloro --> 
                <!-- ***************************** --> 
                <div class="row" id="secctionCloro" hidden>
                  <div class="col-md-7">
                     <table class="table" id="">
                        <thead>
                          <tr>
                            <th>Formula </th>
                            <th>Expresión</th>
                            <th>Resultado</th>
                            <th># Decimal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr onclick="habilitarTabla('tableCloroBlanco','tableCloroValoracion')">
                            <td>Blanco Análisis</td>
                            <td>A</td>
                            <td><input type="text" value="" id="blancoResClo" disabled></td>
                            <td>2</td>
                          </tr>
                          <tr onclick="habilitarTabla('tableCloroValoracion','tableCloroBlanco')">
                            <td>Normalidad Real</td>
                            <td>((A*B*C) /D) </td>
                            <td><input type="text" value="" id="normalidadResCloro" disabled></td>
                            <td>3</td>
                          </tr> 
                        </tbody>
                     </table>
                  </div>
                  <div class="col-md-5">
                    <table class="table" id="tableCloroBlanco"  hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>B</td>
                          <td>Blanco</td>
                          <td><input type="text" id="blancoCloro"></td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table" id="tableCloroValoracion" hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>C</td>
                          <td>mL Titulado 1</td>
                          <td><input type="number" id="tituladoClo1"></td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>mL Titulado 2</td>
                          <td><input type="number" id="tituladoClo2"></td>
                        </tr>
                        <tr>
                          <td>E</td>
                          <td>mL Titulado 3</td>
                          <td><input type="number" id="tituladoClo3"></td>
                        </tr>
                        <tr>
                          <td>A</td>
                          <td>mL de NqCI Trazable</td>
                          <td><input type="number" id="trazableClo" value="10"></td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>Normalidad teorica</td>
                          <td><input type="number" id="normalidadClo" value="0.014"></td>
                        </tr>
                      </tbody>
                    </table>
                 </div>
                </div> 
                <!-- ***************************** --> 
                <!-- Fin Cloro --> 
                <!-- ***************************** --> 

                <!-- ***************************** --> 
                <!-- Inicio Dqo --> 
                <!-- ***************************** --> 
                <div class="row" id="secctionDqo" hidden>
                  <div class="col-md-7">
                     <table class="table" id="">
                        <thead>
                          <tr>
                            <th>Formula</th>
                            <th>Expresión</th>
                            <th>Resultado</th>
                            <th># Decimal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr onclick="habilitarTabla('tableDqoBlanco','tableDqoValoracion')">
                            <td>Blanco Análisis</td>
                            <td>A</td>
                            <td><input type="text" value="" id="blancoResD" disabled></td>
                            <td>2</td>
                          </tr>
                          <tr onclick="habilitarTabla('tableDqoValoracion','tableDqoBlanco')">
                            <td>Molaridad</td>
                            <td>((A*B*C) /D) </td>
                            <td><input type="text" value="" id="molaridadResD" disabled></td>
                            <td>3</td>
                          </tr> 
                        </tbody>
                     </table>
                  </div>
                  <div class="col-md-5">
                    <table class="table" id="tableDqoBlanco"  hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>B</td>
                          <td>Mililitros titulados</td>
                          <td><input type="text" id="blancoValD"></td>
                          <td>V</td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table" id="tableDqoValoracion" hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>A</td>
                          <td>Vol. de K2Cr207</td>
                          <td><input type="number" id="volk2D" value="10"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>concentracion</td>
                          <td><input type="number" id="concentracionD" value="0.04"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>C</td>
                          <td>Factor de equivalentes</td>
                          <td><input type="number" id="factorD" value="6"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>Vol. titulado</td>
                          <td><input type="number" id="titulado1D"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>E</td>
                          <td>Vol. titulado 2</td>
                          <td><input type="number" id="titulado2D"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>G</td>
                          <td>Vol. titulado 3</td>
                          <td><input type="number" id="titulado3D"></td>
                          <td>V</td>
                        </tr>
                      </tbody>
                    </table>
                 </div>
                </div> 
                <!-- ***************************** --> 
                <!-- Fin Dqo --> 
                <!-- ***************************** --> 

                <!-- ***************************** --> 
                <!-- Inicio Nitrogeno --> 
                <!-- ***************************** --> 
                <div class="row" id="secctionNitrogeno" hidden>
                  <div class="col-md-7">
                     <table class="table" id="">
                        <thead>
                          <tr>
                            <th>Formula</th>
                            <th>Expresión</th>
                            <th>Resultado</th>
                            <th># Decimal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr onclick="habilitarTabla('tableNBlanco','tableNValoracion')">
                            <td>Blanco Análisis</td>
                            <td>A</td>
                            <td><input type="text" value="" id="blancoResN" disabled></td>
                            <td>2</td>
                          </tr>
                          <tr onclick="habilitarTabla('tableNValoracion','tableNBlanco')">
                            <td>Molaridad N2</td>
                            <td>((A*B*C) /D) </td>
                            <td><input type="text" value="" id="molaridadResN" disabled></td>
                            <td>3</td>
                          </tr> 
                        </tbody>
                     </table>
                  </div>
                  <div class="col-md-5">
                    <table class="table" id="tableNBlanco"  hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>B</td>
                          <td>Mililitros titulados</td>
                          <td><input type="text" id="blancoValN"></td>
                          <td>V</td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table" id="tableNValoracion" hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>A</td>
                          <td>gramos de Na2CO3</td>
                          <td><input type="number" id="gramosN" value="0.0318"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>Factor conversion</td>
                          <td><input type="number" id="factorN" value="1000"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>C</td>
                          <td>Mililitro 1 Titulado</td>
                          <td><input type="number" id="titulado1N"></td> 
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>Mililitro 2 Titulado</td>
                          <td><input type="number" id="titulado2N"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>E</td>
                          <td>Mililitro 3 Titulado</td>
                          <td><input type="number" id="titulado3N"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>G</td>
                          <td>Pm del Na2CO3</td>
                          <td><input type="number" id="PmN" value="106"></td>
                          <td>C</td>
                        </tr>
                      </tbody>
                    </table>
                 </div>
                </div> 
                <!-- ***************************** --> 
                <!-- Fin Nitrogeno --> 
                <!-- ***************************** --> 
              </div> 

   

              {{-- DQO --}}
              <div class="tab-pane fade" id="dqo" role="tabpanel" aria-labelledby="dqo-tab">  
              <div class="row">
                <div class="col-md-3">
                  <h4>Tipo de Lote DQO</h4>

                  <select id="tipoDqo">
                    <option value="0">Selecionar</option>  
                    <option value="1">DQO ALTA</option>
                    <option value="2">DQO BAJA</option> 
                    <option value="3">DQO TUBO SELLADO ALTA</option> 
                    <option value="4">DQO TUBO SELLADO BAJA</option> 
                  </select>
                </div>
                <div class="col-md-3">
                  <h4>Tecnica DQO</h4>

                  <select id="tecnicaDqo">
                    <option value="0">Selecionar</option>  
                    <option value="1">Espectrofotometria</option>
                    <option value="2">Volumetria</option> 
                  </select>
                </div>
                <div class="col-md-6">
                  <button class="btn btn-sm btn-success" id="btnGuardarTipoDqo">Guardar</button>
                </div>
              </div>

              <hr>

                <h4>Ebullición</h4>
                <label>Lote ID: <input type="text" id="ebullicion_loteId"></label> <br>
                <label>Inicio: <input type="time" id="ebullicion_inicio"></label>
                <label>Fin: <input type="time" id="ebullicion_fin"></label><br><br>

                <p>Bureta utilizada para titulación</p>
                <label>INVLAB: <input type="text" id="ebullicion_invlab"></label> <br>
              </div>
              {{-- DQO FIN --}}

              {{-- PROCEDIMIENTO --}}
              <div class="tab-pane fade" id="procedimiento" role="tabpanel" aria-labelledby="procedimiento-tab">                                
              
                
                <div id="divSummer">
                  <div id="summernote">
                        @if (isset($textoRecuperado))
                        @php
                            echo $textoRecuperado->Texto;
                        @endphp
                      @else
                        @php
                            echo $textoRecuperadoPredeterminado->Texto;
                        @endphp
                    @endif       
                  </div>
                </div>
             
                
                <button type="button" class="btn btn-primary" onclick='guardarTexto("idLoteHeader");'>Guardar</button>
              </div>
              {{-- PROCEDIMIENTO FIN --}}
            </div>
          </div>
          
          <div class="col-md-12">
            <div id="inputVar">

            </div> 
          </div>
         
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      
      </div>
    </div>
  </div>
</div>

  @stop

  @section('css')
    <link rel="stylesheet" href="{{ asset('/css/laboratorio/fq/lote.css')}}">    
  @endsection

  @section('javascript') 
  <!-- include summernote css/js -->
  <script src="{{asset('/assets/summer/summernote.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('public/js/laboratorio/fq/loteVol.js')}}?v=0.0.3"></script>
@endsection