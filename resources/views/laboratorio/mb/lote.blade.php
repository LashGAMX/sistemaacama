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
    <div cla ss="col-md-12">
      <div class="row">   
         
        <div class="col-md-3">
          <div class="form-group">
            <label for="exampleFormControSelect1">Tipo fórmula</label>
              <select class="form-control" id="tipo">
                @foreach($parametro as $item)
                  <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
                @foreach($parametro1 as $item)
                  <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
                @foreach($parametro2 as $item)
                  <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
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
                  <option value="{{$item->Id_parametro}}">{{$item->Id_parametro}}/{{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
                @foreach($parametro1 as $item)
                  <option value="{{$item->Id_parametro}}">{{$item->Id_parametro}}/{{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
                @foreach($parametro2 as $item)
                  <option value="{{$item->Id_parametro}}">{{$item->Id_parametro}}/{{$item->Parametro}} ({{$item->Tipo_formula}})</option>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">FLAMA</h5>
        
        <label>ID Lote: <input type="text" id="idLoteHeader" size=10 /> <button class="btn btn-info" onclick="getDatalote()">Buscar</button></label>
        <label>Fecha Lote: <input type="datetime-local" id="fechaLote"/></label>                
        <!-- <div class="form-check" id="cierreCaptura">
          <label class="form-check-label" for="flexCheckDefault">
            Cierre captura:
          </label>
          <input class="form-check-input" type="checkbox" id="cierreCaptura">        
        </div> -->
        <button type="button" class="btn btn-success" id="btnGuardar">Guardar</button>        
        <!-- <button type="button" class="btn btn-danger">Salir</button> -->
        <div id="btnRefresh"></div>        
        <ul class="nav nav-tabs" id="myTab" role="tablist"> 
          
          <li class="nav-item" role="menu">
            <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true" onclick='isSelectedProcedimiento("formulaGlobal-tab");'>Fórmulas Globales</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="coliformes-tab" data-toggle="tab" href="#coliformes" role="tab" aria-controls="coliformes" aria-selected="false">Coliformes</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="dbo-tab" data-toggle="tab" href="#dbo" role="tab" aria-controls="dbo" aria-selected="false">DBO</a>
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
                <div class="col-md-12">    
                  <div id="divTableFormulaGlobal">
                    <table class="table" id=""> 
                      <thead>
                        <tr>
                          <th scope="col">Fórmula</th>
                          {{-- <th scope="col">Fórmula</th> --}}
                          <th scope="col">Resultado</th>
                          <th scope="col">Núm.Decimales</th>
                          {{-- <th scope="col">FechaInicio</th> --}}
                          {{-- <th scope="col">PruebaDesplazamiento</th> --}}
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>
             
                </div>                                

              </div> 


              {{-- COLIFORMES --}}
              <div class="tab-pane fade" id="coliformes" role="tabpanel" aria-labelledby="coliformes-tab">  
                <button id="btnColiformes" class="btn btn-succcess">Guardar</button>
                <h4>Sembrado</h4>
                <hr>

                {{-- <label>Lote ID: <input type="text" id="sembrado_loteId"></label> <br> --}}
                <label>Sembrado: <input type="datetime-local" id="sembrado_sembrado"></label><br>
                <label>Fecha de resiembra de la cepa utilizada: <input type="date" id="sembrado_fechaResiembra"></label><br>
                <label>Tubo N°: <input type="text" id="sembrado_tuboN"></label> <br>
                <label>Bitácora: <input type="text" id="sembrado_bitacora"></label>

                <br><br>

                <h4>Prueba Presuntiva</h4>
                <hr>
                
                <label>Preparación: <input type="datetime-local" id="pruebaPresuntiva_preparacion"></label><br>
                <label>Lectura: <input type="datetime-local" id="pruebaPresuntiva_lectura"></label><br>

                <br>

                <h4>Prueba confirmativa</h4>
                <hr>

                <label>Medio: <input type="text" id="pruebaConfirmativa_medio"></label> <br>
                <label>Preparación: <input type="datetime-local" id="pruebaConfirmativa_preparacion"></label><br>
                <label>Lectura: <input type="datetime-local" id="pruebaConfirmativa_lectura"></label><br>                
              </div> 
              {{-- COLIFORMES FIN --}}

              {{-- DBO --}}
              <div class="tab-pane fade" id="dbo" role="tabpanel" aria-labelledby="dbo-tab">  
                  <div class="row">
                    <div class="col-md-4">
                      <label>Cantidad de dilucion: <input class="form-control" type="text" id="cantDilucion"></label>
                    </div>
                    <div class="col-md-4">
                      <label>De: <input class="form-control" type="time" id="de"></label>
                    </div>
                    <div class="col-md-4">
                      <label>A: <input class="form-control" type="time" id="a"></label>
                    </div>
                    <div class="col-md-4">
                      <label>Pag: <input class="form-control" type="text" id="pag"></label>
                    </div>
                    <div class="col-md-4">
                      <label>N: <input class="form-control" type="text" id="n"></label>
                    </div>
                    <div class="col-md-4">
                      <label>Dilución: <input class="form-control" type="text" id="dilucion"></label>
                    </div>
                    <div class="col-md-12">
                      <button class="btn btn-successs" id="btnGuardarDqo">Guardar</button>
                    </div>
                  </div>
              </div>
              {{-- DBO FIN --}}

             

            
              {{-- PROCEDIMIENTO --}}
              <div class="tab-pane fade" id="procedimiento" role="tabpanel" aria-labelledby="procedimiento-tab">                                
              
                
                <div id="divSummer">
                  <div id="summernote">
                       
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerra</button>
      
      </div>
    </div>
  </div>
</div>

  @stop

  @section('css')
    <link rel="stylesheet" href="{{ asset('/public/css/laboratorio/mb/lote.css')}}">    
  @endsection

  @section('javascript')
  <!-- include summernote css/js -->
  <script src="{{asset('/public/assets/summer/summernote.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('/public/js/laboratorio/mb/lote.js')}}"></script>
@endsection