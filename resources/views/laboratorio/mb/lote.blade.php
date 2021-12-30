@extends('voyager::master')

@section('content')
<link rel="stylesheet" href="{{asset('assets/summer/summernote.min.css')}}">
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
              @foreach($formulas as $formula)
                <option value="{{$formula->Id_tipo_formula}}">{{$formula->Tipo_formula}}</option>
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
                @foreach($formulas as $formula)
                  <option value="{{$formula->Id_tipo_formula}}">{{$formula->Tipo_formula}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="exampleFormControSelect1">Tecnica análisis</label> 
              <select class="form-control" id="teecn">
                @foreach($tecnica as $item)
                  <option value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
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
        <button type="button" class="btn btn-success" id="guardarTodo">Guardar</button>        
        <!-- <button type="button" class="btn btn-danger">Salir</button> -->
        <div id="btnRefresh"></div>        
        <ul class="nav nav-tabs" id="myTab" role="tablist"> 
          
          <li class="nav-item" role="menu">
            <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true" onclick='isSelectedProcedimiento("formulaGlobal-tab");'>Fórmulas Globales</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="grasas-tab" data-toggle="tab" href="#grasas" role="tab" aria-controls="grasas" aria-selected="false">Grasas</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="coliformes-tab" data-toggle="tab" href="#coliformes" role="tab" aria-controls="coliformes" aria-selected="false">Coliformes</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="dbo-tab" data-toggle="tab" href="#dbo" role="tab" aria-controls="dbo" aria-selected="false">DBO</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="dqo-tab" data-toggle="tab" href="#dqo" role="tab" aria-controls="dqo" aria-selected="false">DQO</a>
          </li>

          <li class="nav-item" role="menu">
            <a class="nav-link" id="metales-tab" data-toggle="tab" href="#metales" role="tab" aria-controls="metales" aria-selected="false">Metales</a>
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
                          <!-- <td>Blanco Cobre</td> -->
                          {{-- <td></td> --}}
                          <!-- <td>0</td> -->
                          <!-- <td>3</td> -->
                          {{-- <td contenteditable="true"><input type="date-time" id="fechaInicio" size="17"></td> --}}
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>
             
                </div>                                

              </div> 
               
              {{-- GRASAS --}}
              <div class="tab-pane fade" id="grasas" role="tabpanel" aria-labelledby="grasas-tab">                                

                <div class="container">
                    <div class="row">
                        <div class="">
                            <div class="vertical-tab" role="tabpanel">

                            
                                <ul class="nav flex-column" role="tablist">
                                    <li role="presentation"><a href="#calentamiento" aria-controls="home" role="tab" data-toggle="tab">Calentamiento de matraces</a></li>
                                    <li role="presentation"><a href="#enfriamiento" aria-controls="profile" role="tab" data-toggle="tab">Enfriado de matraces</a></li>
                                    <li role="presentation"><a href="#secado" aria-controls="messages" role="tab" data-toggle="tab">Secado de cartuchos</a></li>
                                    <li role="presentation"><a href="#tiempo" aria-controls="messages" role="tab" data-toggle="tab">Tiempo de reflujo</a></li>
                                    <li role="presentation"><a href="#enfriado2" aria-controls="messages" role="tab" data-toggle="tab">Enfriado de matraces</a></li>
                                </ul>
                                
                                <div class="tab-content tabs">
                                    <div role="tabpanel" class="tab-pane fade tablas" id="calentamiento">                                        
                                          <h5>Calentamiento de matraces</h5>
                                          
                                          <table class="table" id="calentamiento_matraces"> 
                                            <thead>
                                              <tr>
                                                <th scope="col" style="text-align: center"><input type="number" class="entradas"></th>
                                                <th scope="col" style="text-align: center"><input type="number" class="entradas"></th>
                                                <th scope="col" style="text-align: center"><input type="number" class="entradas"></th>
                                                <th scope="col" style="text-align: center"><input type="time"></th>
                                                <th scope="col" style="text-align: center"><input type="time"></th>
                                              </tr>
                                              <tr>
                                                <th scope="col" style="text-align: center">ID Lote</th>
                                                <th scope="col" style="text-align: center">Masa constante</th>
                                                <th scope="col" style="text-align: center">Temperatura</th>
                                                <th scope="col" style="text-align: center">Hora de entrada</th>
                                                <th scope="col" style="text-align: center">Hora de salida</th>
                                              </tr>
                                            </thead>                                            
  
                                            <tbody>
                                              @for ($i = 1; $i <= 3; $i++)
                                                <tr>                                              
                                                  <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:100%" id="calLote{{$i}}" value="1" disabled></input></td> 
                                                  <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:100%" id="calMasa{{$i}}" value="{{$i}}" disabled></input></td>
                                                  <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="height: 100%; width:100%" id="calTemp{{$i}}"></td>
                                                  <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="calEntrada{{$i}}"></td>
                                                  <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="calSalida{{$i}}"></td>
                                                </tr>
                                              @endfor                                                                                        
                                            </tbody>
                                          </table>
                                      </div>

                                    <div role="tabpanel" class="tab-pane fade tablas" id="enfriamiento">
                                        <h5>Enfriado de matraces</h5>

                                        <table class="table" id="enfriamiento_matraces"> 
                                          <thead>
                                            <tr>
                                              <th scope="col" style="text-align:center"><input type="number" class="entradas"></th>
                                              <th scope="col" style="text-align:center"><input type="number" class="entradas"></th>
                                              <th scope="col" style="text-align:center"><input type="time"></th>
                                              <th scope="col" style="text-align:center"><input type="time"></th>   
                                              <th></th>                                                                                         
                                            </tr>
                                            <tr>
                                              <th scope="col" style="text-align: center">ID Lote</th>
                                              <th scope="col" style="text-align: center">Masa constante</th>                                              
                                              <th scope="col" style="text-align: center">Hora de entrada</th>
                                              <th scope="col" style="text-align: center">Hora de salida</th>
                                              <th scope="col" style="text-align: center">Pesado de matraces</th>
                                            </tr>
                                          </thead>

                                          <tbody>

                                            @for ($i = 1; $i <= 3; $i++)
                                              <tr>                                              
                                                <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:100%" id="enfLote{{$i}}" value="1" disabled></input></td>                                              
                                                <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:100%" id="enfMasa{{$i}}" value="{{$i}}" disabled></input></td>
                                                <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="enfEntrada{{$i}}"></td>                                              
                                                <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="enfSalida{{$i}}"></td>
                                                <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="height: 100%; width:100%" id="enfPesado{{$i}}"></td>
                                              </tr>
                                            @endfor
                                            
                                          </tbody>
                                        </table>                                        
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade tablas" id="secado">
                                        <h5>Secado de cartuchos</h5>

                                        <table class="table" id="secado_cartuchos"> 
                                          <thead>
                                            <tr>
                                              <th scope="col" style="text-align:center"><input type="number" class="entradas"></th>
                                              <th scope="col" style="text-align:center"><input type="number" class="entradas"></th>
                                              <th scope="col" style="text-align:center"><input type="time"></th>
                                              <th scope="col" style="text-align:center"><input type="time"></th>                                                                                            
                                            </tr>
                                            <tr>
                                              <th scope="col" style="text-align: center">ID Lote</th>
                                              <th scope="col" style="text-align: center">Temperatura</th>                                              
                                              <th scope="col" style="text-align: center">Hora de entrada</th>
                                              <th scope="col" style="text-align: center">Hora de salida</th>                                              
                                            </tr>
                                          </thead>

                                          <tbody>
                                            <tr>                                              
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:120px" id="secadoLote1" value="1" disabled></input></td>
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="height:100%; width:120px" id="secadoTemp1"></td>
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="secadoEntrada1"></td>
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="secadoSalida1"></td>
                                            </tr>                                                                                        
                                          </tbody>
                                        </table>
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade tablas" id="tiempo">
                                      <h5>Tiempo de reflujo</h5>

                                      <table class="table" id="tiempo_reflujo"> 
                                        <thead>
                                          <tr>
                                            <th scope="col" style="text-align:center"><input type="number" class="entradas"></th>                                            
                                            <th scope="col" style="text-align:center"><input type="time"></th>
                                            <th scope="col" style="text-align:center"><input type="time"></th>                                                                                            
                                          </tr>
                                          <tr>
                                            <th scope="col" style="text-align: center">ID Lote</th>                                            
                                            <th scope="col" style="text-align: center">Hora de entrada</th>
                                            <th scope="col" style="text-align: center">Hora de salida</th>                                              
                                          </tr>
                                        </thead>

                                        <tbody>
                                          <tr>                                              
                                            <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:120px" id="tiempoLote1" value="1" disabled></input></td>
                                            <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="tiempoEntrada1"></td>
                                            <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="tiempoSalida1"></td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade tablas" id="enfriado2">
                                        <h5>Enfriado de matraces</h5>

                                        <table class="table" id="enfriado_matraz"> 
                                          <thead>
                                            <tr>
                                              <th scope="col" style="text-align:center"><input type="number" class="entradas"></th>                                            
                                              <th scope="col" style="text-align:center"><input type="time"></th>
                                              <th scope="col" style="text-align:center"><input type="time"></th>                                                                                            
                                            </tr>
                                            <tr>
                                              <th scope="col" style="text-align: center">ID Lote</th>                                            
                                              <th scope="col" style="text-align: center">Hora de entrada</th>
                                              <th scope="col" style="text-align: center">Hora de salida</th>                                              
                                            </tr>
                                          </thead>

                                          <tbody>
                                            <tr>                                              
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="number" style="text-align:center;height:100%;width:120px" id="enfriadoLote1" value="1" disabled></input></td>
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="enfriadoEntrada1"></td>                                              
                                              <td style="text-align:center;padding: 10px 10px;width:120px"><input type="time" id="enfriadoSalida1"></td>
                                            </tr>                                                                                        
                                          </tbody>
                                        </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                
                <!-- <div class="container">
                    <div class="row">
                        <div class="">
                            <div class="vertical-tab" role="tabpanel">

                            
                                <ul class="nav flex-column" role="tablist">
                                    <li role="presentation"><a href="#calentamiento" aria-controls="home" role="tab" data-toggle="tab">Calentamiento de matraces</a> 
                                      <div class="tab-content tabs">
                                        <div role="tabpanel" class="tab-pane fade in active" id="calentamiento">
                                          <h3>Calentamiento de matrace</h3>
                                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce semper, magna. </p>
                                        </div>
                                      </div>
                                    </li>
                                    <li role="presentation"><a href="#enfriamiento" aria-controls="profile" role="tab" data-toggle="tab">Enfriamiento de matraces</a>
                                      <div class="tab-content tabs">                                          
                                          <div role="tabpanel" class="tab-pane fade" id="enfriamiento">
                                              <h3>Enfriamiento de matraces</h3>
                                              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce semper, magna a ultricies volutpat, mi eros viverra massa, vitae consequat nisi justo in tortor. Proin accumsan felis ac felis dapibus, non iaculis mi varius, mi eros viverra massa.</p>
                                          </div>                                          
                                      </div>
                                    </li>
                                    <li role="presentation"><a href="#secado" aria-controls="messages" role="tab" data-toggle="tab">Secado de cartuchos</a></li>
                                </ul>                                                                
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="tab-content tabs">
                        <div role="tabpanel" class="tab-pane fade in active" id="calentamiento">
                          <h3>Calentamiento de matrace</h3>
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce semper, magna. </p>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="enfriamiento">
                          <h3>Enfriamiento de matraces</h3>
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce semper, magna a ultricies volutpat, mi eros viverra massa, vitae consequat nisi justo in tortor. Proin accumsan felis ac felis dapibus, non iaculis mi varius, mi eros viverra massa.</p>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="secado">
                          <h3>Secado de cartuchos</h3>
                          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce semper, magna a ultricies volutpat, mi eros viverra massa, vitae consequat nisi justo in tortor. Proin accumsan felis ac felis dapibus, non iaculis mi varius, mi eros viverra massa.</p>
                      </div>
                    </div> -->

                    <!-- <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Calentamiento de matraces
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <h3>Calentamiento de matraces</h3>
                        <p>Hola Mundo</p>
                      </div>
                    </div>   
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Enfriamiento de matraces
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu3">
                        <h3>Calentamiento de matraces</h3>
                        <p>Hola Mundo</p>
                      </div>
                    </div>  -->                             

              </div>
              {{-- GRASAS FIN --}}

              {{-- COLIFORMES --}}
              <div class="tab-pane fade" id="coliformes" role="tabpanel" aria-labelledby="coliformes-tab">  
                <h4>Sembrado</h4>
                <hr>

                <label>Lote ID: <input type="text" id="sembrado_loteId"></label> <br>
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
              
              </div>
              {{-- DBO FIN --}}

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

              {{-- METALES --}}
              <div class="tab-pane fade" id="metales" role="tabpanel" aria-labelledby="metales-tab">  
              
              </div>
              {{-- METALES FIN --}}
            
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
    <link rel="stylesheet" href="{{ asset('css/laboratorio/fq/lote.css')}}">    
  @endsection

  @section('javascript')
  <!-- include summernote css/js -->
  <script src="{{asset('assets/summer/summernote.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('js/laboratorio/mb/lote.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>  
@endsection