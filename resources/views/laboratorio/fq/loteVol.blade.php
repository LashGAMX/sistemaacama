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
            <select class="form-control" id="tipo">
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
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="row"> 
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalCrearLote" class="btn btn-info">Crear lote</button>
        </div>
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalProbar" class="btn btn-info">Datos lote</button>
        </div>
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalFq2" class="btn btn-info">Nueva función</button>
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
          {{-- <tr>
            <th scope="col">Cerrado</th>
            <th scope="col">AnaFórmulaId</th>
            <th scope="col">RcpLoteAnálisisId</th>
            <th scope="col">Fórmula</th>
            <th scope="col">TipoFórmulaBitácora</th>
            <th scope="col">FechaLote</th>
            <th scope="col">FechaHora</th>
            <th scope="col">FechaNuevoLote</th>
            <th scope="col">HoraNuevoLote</th>
          </tr> --}}
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
  <div class="modal-dialog modal-lg" style="width: 80%">
    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">FLAMA</h5>
        
        <label>ID Lote: <input type="text" id="idLoteHeader" size=10 /> <button class="btn btn-info" onclick="getDatalote();">Buscar</button></label>
        <label>Fecha Lote: <input type="datetime-local" id="fechaLote"/></label>                
        <!-- <div class="form-check" id="cierreCaptura">
          <label class="form-check-label" for="flexCheckDefault">
            Cierre captura:
          </label>
          <input class="form-check-input" type="checkbox" id="cierreCaptura">        
        </div> -->
        <button type="button" class="btn btn-success" id="guardarTodo">Guardar</button>        
        <!-- <button type="button" class="btn btn-danger">Salir</button> -->
        <div id="btnRefresh"></div>        
        <ul class="nav nav-tabs" id="myTab" role="tablist"> 
          
          <li class="nav-item" role="menu">
            <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true" onclick='isSelectedProcedimiento("formulaGlobal-tab");'>Fórmulas Globales</a>
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
                  <div class="col-md-7">
                     <table class="table">
                        <thead>
                          <tr>
                            <th>Formula</th>
                            <th>Expresión</th>
                            <th>Resultado</th>
                            <th># Decimal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Blanco Análisis</td>
                            <td>A</td>
                            <td><input type="text" value="" disabled></td>
                            <td>2</td>
                          </tr>
                          <tr>
                            <td>Molaridad</td>
                            <td>((A*B*C) /D) </td>
                            <td><input type="text" value="" disabled></td>
                            <td>3</td>
                          </tr> 
                        </tbody>
                     </table>
                  </div>
                  <div class="col-md-5">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>A</td>
                          <td>Vol. de K2Cr207</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>concentracion</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>C</td>
                          <td>Factor de equivalentes</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>Vol. titulado</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>E</td>
                          <td>Vol. titulado 2</td>
                          <td><input type="text"></td>
                        </tr>
                        <tr>
                          <td>G</td>
                          <td>Vol. titulado 3</td>
                          <td><input type="text"></td>
                        </tr>
                      </tbody>
                    </table>
                 </div>
                </div>                             
              </div> 

   

              {{-- DQO --}}
              <div class="tab-pane fade" id="dqo" role="tabpanel" aria-labelledby="dqo-tab">  
                <h4>Ebullición</h4>
                <hr>

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
  <script src="{{asset('/public/js/laboratorio/fq/loteVol.js')}}"></script>
@endsection