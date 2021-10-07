@extends('voyager::master')

@section('content')

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
            <select class="form-control">
              @foreach($formulas as $formula)
                <option value="{{$formula->Id_tipo_formula}}">{{$formula->Tipo_formula}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="">Fecha lote</label>
            <input type="date" class="form-control" placeholder="Fecha lote">
          </div>
        </div>
        
        <div class="col-md-2">
          <button class="btn btn-success">Buscar</button>
        </div>        
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalProbar" class="btn btn-info">Crear lote</button>
        </div>
      </div>

      <table class="table" id="tableObservacion"> 
        <thead>
          <tr>
            <th scope="col">Cerrado</th>
            <th scope="col">AnaFórmulaId</th>
            <th scope="col">RcpLoteAnálisisId</th>
            <th scope="col">Fórmula</th>
            <th scope="col">TipoFórmulaBitácora</th>
            <th scope="col">FechaLote</th>
            <th scope="col">FechaHora</th>
            <th scope="col">FechaNuevoLote</th>
            <th scope="col">HoraNuevoLote</th>
          </tr>
        </thead>
        <tbody>
                  
        </tbody>
      </table>
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
        

        <label>ID Lote: <input type="text" id="idLoteHeader" size=10/  onchange='busquedaPlantilla("idLoteHeader");'></label>
        <label>Fecha Lote: <input type="datetime-local" id="fechaLote"/></label>                
        <button type="button" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-danger">Salir</button>
        <div class="form-check">
          <label class="form-check-label" for="flexCheckDefault">
            Cierre captura:
          </label>
          <input class="form-check-input" type="checkbox" id="cierreCaptura">        
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist"> 
          <li class="nav-item" role="menu">
            <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true">Fórmulas Globales</a>
          </li>
          <li class="nav-item" role="menu">
            <a class="nav-link" id="equipo-tab" data-toggle="tab" href="#equipo" role="tab" aria-controls="equipo" aria-selected="false">Equipo</a>
          </li>
          <li class="nav-item" role="menu">
            <a class="nav-link" id="procedimiento-tab" data-toggle="tab" href="#procedimiento" role="tab" aria-controls="procedimiento" aria-selected="false">Procedimiento/Validación</a>
          </li>
        </ul>
      </div>

      <div class="modal-body" id="modal">
        <div class="row">
          <div class="col-md-12">            
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade  active" id="formulaGlobal" role="tabpanel" aria-labelledby="formulaGlobal-tab">    
                <div class="col-md-12">                                    

                  <div id="contenedorGeneral">
                    <div id="contenedorIzq">
                      <table class="table" id="tableFormulasGlobales"> 
                        <thead>
                          <tr>
                            <th scope="col">Fórmula</th>
                            <th scope="col">Fórmula</th>
                            <th scope="col">Resultado</th>
                            <th scope="col">Núm.Decimales</th>
                            <th scope="col">FechaInicio</th>
                            <th scope="col">PruebaDesplazamiento</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td contenteditable="true"><input type="date-time" id="fechaInicio" size="17"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <div id="contenedorDer">
                      <table class="table" id="tableFormulasGlobalesValores"> 
                        <thead>
                          <tr>
                            <th scope="col">Parámetro</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Tipo</th>                          
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr>

                          <tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr><tr>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>
                            <td>Hola</td>                          
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div> 
               
              <div class="tab-pane fade" id="equipo" role="tabpanel" aria-labelledby="equipo-tab">  
                <h4>Flama / Generador de hidruros / Horno de grafito / Alimentos</h4>
                <hr>
                <label>Lote ID: <input type="text" id="loteId"></label> &nbsp;&nbsp;&nbsp; 
                <label>Fecha / Hora digestión: <input type="datetime-local" id="fechaHoraDig"></label><br>
                <label>Longitud de onda: <input type="text" id="longOnda"></label> &nbsp;&nbsp;&nbsp; 
                <label>Flujos de gas: <input type="text" id="flujoGas"></label> &nbsp;&nbsp;&nbsp;
                <label>Equipo: <input type="text" id="equipoForm"></label><br>
                <label>No. de inventario: <input type="text" id="numInventario"></label> &nbsp;&nbsp;&nbsp;
                <label>No. de inv. de lámpara: <input type="text" id="numInvLamp"></label> &nbsp;&nbsp;&nbsp;
                <label>Slit: <input type="text" id="slit" size="17"></label><br>
                <label>Corriente: <input type="text" id="corriente"></label> &nbsp;&nbsp;&nbsp;
                <label>Energía: <input type="text" id="energia"></label> &nbsp;&nbsp;&nbsp;
                <label>Conc. Std: <input type="text" id="concStd"></label><br>
                <label>Gas: <input type="text" id="gas"></label> &nbsp;&nbsp;&nbsp;
                <label>Aire: <input type="text" id="aire"></label> &nbsp;&nbsp;&nbsp;
                <label>Óxido nitroso: <input type="text" id="oxidoN"></label><br><br>
                
                <h4>Blanco de curva</h4>
                <hr>
                <label>Verificación de blanco: <input type="text" id="verifBlanco"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS. Teórica blanco: <input type="text" id="absTeoBlanco"></label><br>
                <label>ABS 1: <input type="text" id="abs1" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 2: <input type="text" id="abs2" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 3: <input type="text" id="abs3" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 4: <input type="text" id="abs4" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 5: <input type="text" id="abs5" size="10"></label><br>
                <label>ABS promedio: <input type="text" id="absProm"></label> &nbsp;&nbsp;&nbsp;
                <label>Conclusión blanco: <input type="text" id="concBlanco"></label><br><br>
                
                <h4>Verificación del espectómetro</h4>
                <hr>
                <label>STD. Cal: <input type="text" id="stdCal"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS. Teórica: <input type="text" id="absTeorica"></label> &nbsp;&nbsp;&nbsp;
                <label>Conc. (mg/L): <input type="text" id="concMgL"></label><br>
                <label>ABS 1: <input type="text" id="verifAbs1" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 2: <input type="text" id="verifAbs2" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 3: <input type="text" id="verifAbs3" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 4: <input type="text" id="verifAbs4" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 5: <input type="text" id="verifAbs5" size="10"></label><br>
                <label>ABS promedio: <input type="text" id="verifAbsProm" size="7"></label> &nbsp;&nbsp;&nbsp;
                <label>Masa característica (pg/0.0044 A-s): <input type="text" id="masaCarac" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>Conclusión: <input type="text" id="conclusion"></label><br>
                <label>Conc. Obtenida: <input type="text" id="conclusionObtenida"></label> &nbsp;&nbsp;&nbsp;
                <label>% Rec: <input type="text" id="rec"></label> &nbsp;&nbsp;&nbsp;
                <label>Cumple: <input type="text" id="cumple"></label><br><br>

                <h4>Estándar de verificación del instrumento</h4>
                <hr>
                <label>Conc. (mg/L): <input type="text" id="stdConc"></label> &nbsp;&nbsp;&nbsp;
                <label>DESV. STD.: <input type="text" id="desvStd"></label> &nbsp;&nbsp;&nbsp;
                <label>Cumple: <input type="text" id="stdCumple"></label><br>
                <label>ABS 1: <input type="text" id="stdAbs1" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 2: <input type="text" id="stdAbs2" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 3: <input type="text" id="stdAbs3" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 4: <input type="text" id="stdAbs4" size="10"></label> &nbsp;&nbsp;&nbsp;
                <label>ABS 5: <input type="text" id="stdAbs5" size="10"></label><br><br>

                <h4>Curva de calibración</h4>
                <hr>
                <label>Bitácora curva calibración: <input type="text" id="bitCurvaCal"></label> &nbsp;&nbsp;&nbsp;
                <label>Folio curva de calibración: <input type="text" id="folioCurvaCal"></label><br><br>

                <h4>Generador de Hidruros</h4>
                <hr>
                <label>Generador de Hidruros: <input type="text" id="genHidruros"></label>
              </div> 
            
              <div class="tab-pane fade" id="procedimiento" role="tabpanel" aria-labelledby="procedimiento-tab">                                
              
                <div id="editor">
                  <!--COLOCAR EL TEXTO ALMACENADO DE LA BD; PENDIENTE-->
                  
                  
                </div>

                <button type="button" class="btn btn-primary" onclick='guardarTexto("editor");'>Guardar</button>
              </div> 
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
    <link rel="stylesheet" href="{{ asset('css/laboratorio/lote.css')}}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  @endsection

  @section('javascript')
  <script src="{{asset('js/laboratorio/lote.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  @stop

@endsection