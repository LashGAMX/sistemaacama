@extends('voyager::master')
@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
<link rel="stylesheet" href="{{asset('/public/css/laboratorio/analisis/captura.css')}}">

<div class="container-fluid">
  <div class="row"> 
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-6">
          <center>
            <h4 class="text-info">Lotes</h4>
          </center>
          <div id="divLote">
            <table class="table table-sm" id="tabLote">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Fecha</th>
                  <th>Parametro</th>
                  <th>Asignados</th>
                  <th>Liberados</th>
                  <th>Opc</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <center>
          <div id="divUltimoLote"></div>
          </center>
          <div class="row">
            <div class="col-md-6">
              <label for="">Filtros de busqueda</label>
              <div class="form-group">
                <label for="">Parametro</label>
                <select class="form-control select2" id="parametro" onchange="getUltimoLote()">
                  <option value="0">Sin seleccionar</option>
                  @foreach ($model as $item)
                  <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}}
                    ({{$item->Tipo_formula}})</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <input type="date" id="fechaLote" st style="width: 100%">
              </div>
              <div class="form-group">
                <input type="text" id="folio" placeholder="Buscar por folio" style="width: 100%">
              </div>
            </div>
            <div class="col-md-4">
              <label for=""> </label>
              <div class="form-group">
                <button class="btn-info" id="btnBuscarLote" style="width: 100%"><i class="fas fa-search"></i> Buscar
                  lote</button>
              </div>
              <div class="form-group">
                <button class="btn-success" id="btnCrearLote" style="width: 100%"><i class="fas fa-plus"></i> Crear
                  lote</button>
              </div>
              <div class="form-group">
                <button class="btn-primary" style="width: 100%" id="btnPendientes" data-toggle="modal"
                  data-target="#modalPendientes"><i class="voyager-news"></i> Pendientes</button>
              </div>
              <div class="form-group">
                <button class="btn-secundary" style="width: 100%" id="btnHistorial" data-toggle="modal"
                  data-target="#modalHistorial"><i class="voyager-receipt"></i> Historial</button>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <p>Cloruros: <span id="clorurosParametro"></span></p>
            </div>
            <div class="col-md-3">
              <p>Conductividad: <span id="conductividadParametro"></span></p>
            </div>
            <div class="col-md-3">
              <p>pH: <span id="phParametro"></span></p>
            </div>
            <div class="col-md-3">
              <p>% Acep. aprox. : <span id="aceptacion"></span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <center>
        <h4 class="text-success">Captura de resultados</h4>
      </center>
    </div>
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-2">
          <button class="btn-warning" id="btnLiberar"><i class="voyager-check"></i> Liberar</button>
        </div>
        <div class="col-md-2">
          <button class="btn-warning" id="btnLiberarTodo"><i class="fas fa-check-double"></i> Liberar todo</button>
        </div>
        <div class="col-md-2">
          <button class="btn-success" data-toggle="modal" data-target="#modalCalidad"><i class="fas fa-vial"></i>
            Control</button>
        </div>
        <div class="col-md-2">
          <button class="btn-success"><i class="fas fa-vials"></i> Controles</button>
        </div>
        @switch(Auth::user()->id)
        @case(1)
        @case(14)
        @case(100)
        <div class="col-md-2">
         <button class="btn-danger" id="btnEliminarMuestra"><i class="fas fa-trash"></i> Del M.</button>
        </div>
        @break
        @default
        @endswitch
      </div>
    </div>
    <div class="col-md-12" id="divCaptura">
      <table class="table" id="tabCaptura">
        <thead>
          <tr>
            <th>Opc</th>
            <th>Folio</th>
            <th>Norma</th>
            <th>Resultado</th>
            <th>Observacion</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
{{-- Inicio modal pendientes --}}

<div class="modal fade" id="modalPendientes" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pendientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12" id="divPendientes">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Fin modal pendientes --}}
{{-- Inicio modal historial --}}
<div class="modal fade" id="modalHistorial2" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial de analisis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4"><strong>Ph muestra compuesta:</strong> 8</div>
              <div class="col-md-4"><strong>Conductividad:</strong> 3000</div>
              <div class="col-md-4"><strong>Cloruros:</strong> 1500</div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-8">
                <strong>Datos Análisis actual</strong>
              </div>
              <div class="col-md-2">
                <strong>Color:</strong> TURBIO
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <strong>Cliente:</strong> LABACAMA
              </div>    
              <div class="col-md-6">
                <strong>Tipo muestra:</strong> COMPUESTA
              </div>
              <div class="col-md-12">
                <strong>Punto de muestreo:</strong> Lorem ipsum dolor sit amet.
              </div>
              <div class="col-md-6">
                <strong>Norma:</strong> NOM-001-2021
              </div>
              <div class="col-md-6">
                <strong>Parametro:</strong> (7) N-Nitritos
              </div>
              <div class="col-md-6">
                <strong>Anexo:</strong> 1
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <strong>Datos de analisis anterior</strong>
          </div>
          <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <strong>Folio:</strong> 264-125/23-1
                </div>
                <div class="col-md-6">
                  <strong>Punto de muestreo:</strong> Lorem ipsum dolor sit amet.
                </div>
                <div class="col-md-6">
                  <strong>Fecha muestreo:</strong> 10/09/23
                </div>
                <div class="col-md-6">
                  <strong>Resultado:</strong> 8.5
                </div>
              </div>
          </div>


        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal historial-->
<div class="modal fade" id="modalHistorial" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-height: 200%;"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Historial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <span>Lista parametros </span>
                        <div id="divTablaHist">
                          <table id="tablaLoteModal" class="table table-sm">
                              <thead class="thead-dark">
                                  <tr>
                                      <th>Id lote</th>
                                      <th>Fecha lote</th>
                                      <th>Codigo</th>
                                      <th>Parametro</th>
                                      <th>Resultado</th>
                                      <th>His</th>
                                  </tr>
                              </thead>
                              <tbody>
                                      <td>Id lote</td>
                                      <td>Fecha lote</td>
                                      <td>Codigo</td>
                                      <td>Parametro</td>
                                      <td>Resultado</td>
                                      <td>Historial</td>
                              </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div id="divTablaCodigos">
                      <table id="tablaModal2" class="table table-lg">
                          <thead class="thead-dark">
                              <tr>
                                  <th>Id_codigo</th>
                                  <th>Codigo</th>
                                  <th>Parametro</th>
                                  <th>Resultado</th>
                                  <th>Resultado 2</th>
                              </tr>
                          </thead>
                      
                      </table>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>



   <!-- Modal historial-->
   <div class="modal fade" id="modalHistorial" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Historial</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> 
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="tablaLote" class="table table-sm">
                                          <thead class="thead-dark">
                                        <tr>
                                          <td>Id lote</td>
                                          <td>Fecha lote </td>
                                          <td>Codigo</td>
                                          <td>Parametro</td>
                                          <td>Resultado</td>
                                        </tr>
                                          </thead>
                                        </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                      <table id="divtable2" class="table table-sm">
                                      <thead class="thead-dark">
                                        <tr>
                                          <td>Id_codigo</td>
                                          <td>Codigo</td>
                                          <td>Parametro</td>
                                          <td>Resultado</td>
                                          <td>Resultado 2</td>
                                        </tr>
                                        
                                      </thead>
                                      </table>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

 <!-- Modal historial por lote -->
 <div class="modal fade" id="modalHistoriallote" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"  >
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Historial Por Lotes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button> 
                            </div>
                            <div class="modal-body">
                           
  
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="divHistoriallote">
 
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

{{-- Inicio modal Asignar Lote --}}
<div class="modal fade" id="modalAsignar" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignar lote: <input type="text" style="border:none"
            id="loteAsignar"></h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <table>
              <tr>
                <td>Tipo formula:</td>
                <td><input type="text" id="tipoFormulaAsignar" style="border:none"></td>
                <td>Parametro:</td>
                <td style="width: 250px"><input type="text" id="parametroAsignar" style="width: 100%;border:none"></td>
                <td>Fecha analisis:</td>
                <td><input type="text" id="fechaAnalisisAsignar" style="border: none"></td>
              </tr>
            </table>
          </div>
          <div class="col-md-12">
            <center>
              <table>
                <tr>
                  <td>
                    <center>Asignados</center>
                  </td>
                  <td>
                    <center>Liberados</center>
                  </td>
                  <td>
                    <center>Por asignar</center>
                  </td>
                </tr>
                <tr>
                  <td>
                    <center><input type="number" style="border:none;text-align: center" disabled id="asignadoLote">
                    </center>
                  </td>
                  <td>
                    <center><input type="number" style="border:none;text-align: center" disabled id="liberadoLote">
                    </center>
                  </td>
                  <td>
                    <center><input type="number" style="border:none;text-align: center" disabled id="porAsingarLote">
                    </center>
                  </td>
                </tr>
              </table>
            </center>
            <br>
            Fecha recepción: <input type="date" id="fechaAsignar"> <button class="btn-success"><i class="fas fa-search"></i> Buscar</button>
            Muestras seleccionadas: <input type="number" id="muestrasSeleccionadas" style="width: 50px;border:none;color:red">
          </div>
          <div class="col-md-12" id="devAsignarLote">
            <table class="table" id="tabAsignar">
              <thead>
                <tr>
                  <th>Opc</th>
                  <th># Muestra</th>
                  <th>Norma</th>
                  <th>Punto muestreo</th>
                  <th>Fecha recepción</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Fin modal Detalle Lote --}}

<!-- Modal -->
<div class="modal fade" id="modalDetalleLote"  aria-labelledby="modalDetalleLoteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalleLoteLabel">Detalle lote: <input type="" id="tituloLote"
            style="border:none;width: 80%;"></h5>
      </div>
      <div class="modal-body">
        {{-- Inicio de Body --}}
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-toggle="tab" data-target="#general" type="button"
              role="tab" aria-controls="general" aria-selected="true">General</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabGa-tab" data-toggle="tab" data-target="#tabGa" type="button" role="tab"
              aria-controls="tabGa" aria-selected="false">Datos GA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabVol-tab" data-toggle="tab" data-target="#tabVol" type="button" role="tab"
              aria-controls="tabVol" aria-selected="false">Datos Vol</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="dqo-tab" data-toggle="tab" data-target="#tabDqo" type="button" role="tab"
              aria-controls="tabDqo" aria-selected="false">Datos DQO</button>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="coliformes-tab" data-toggle="tab" href="#coliformes" role="tab"
              aria-controls="coliformes" aria-selected="false">Coliformes</a>
          </li>

          <li class="nav-item" role="presentation">
            <a class="nav-link" id="dbo-tab" data-toggle="tab" href="#dbo" role="tab" aria-controls="dbo"
              aria-selected="false">DBO</a>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabAlcalinidad-tab" data-toggle="tab" data-target="#tabAlcalinidad" type="button" role="tab"
              aria-controls="tabAlcalinidad" aria-selected="false">Datos Alcalinidad</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabAcidez-tab" data-toggle="tab" data-target="#tabAcidez" type="button" role="tab"
              aria-controls="tabAcidez" aria-selected="false">Datos Acidez</button>
          </li>



          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tabPlantillas-tab" data-toggle="tab" data-target="#tabPlantillas" type="button"
              role="tab" aria-controls="tabPlantillas" aria-selected="false">Plantilla</button>
          </li>

        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active" id="general" role="tabpanel" aria-labelledby="general-tab">
            Dato 1
          </div>
          <div class="tab-pane fade" id="tabGa" role="tabpanel" aria-labelledby="tabGa-tab">
            <!-- inicio tabla grasas   -->
            <div class="row">
              <div class="col-md-4">
                <button type="button" id="btnSetDetalleGrasas" class="btn-primary"><i class="fas fa-save"></i>
                  Guardar</button>
              </div>
            </div>
            <div class="row">
              <h4>1. Calentamiento de Matraces</h4>
              <input type="datetime-local" id="fechaDefGA">
              <button id="btnFechaDeFGA">Apl F.</button>
              <hr />
              <div class="col-md-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Masa constante</th>
                      <th>Temperatura</th>
                      <th>Hora entrada</th>
                      <th>Hora salida</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td><input type="text" id="tempGA1" /></td>
                      <td><input type="datetime-local" id="entradaGA1" /></td>
                      <td><input type="datetime-local" id="salidaGA1" /></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><input type="text" id="tempGA2" /></td>
                      <td><input type="datetime-local" id="entradaGA2" /></td>
                      <td><input type="datetime-local" id="salidaGA2" /></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><input type="text" id="tempGA3" /></td>
                      <td><input type="datetime-local" id="entradaGA3" /></td>
                      <td><input type="datetime-local" id="salidaGA3" /></td>
                    </tr>
                  </tbody>
                </table>
                <h4>2. Enfriado de Matraces</h4>
                <hr />
                <table class="table">
                  <thead>
                    <tr>
                      <th>Masa constante</th>
                      <th>Hora entrada</th>
                      <th>Hora salida</th>
                      <th>Hora pesado de matraces</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td><input type="datetime-local" id="2entradaGA1" /></td>
                      <td><input type="datetime-local" id="2salidaGA1" /></td>
                      <td><input type="datetime-local" id="2pesadoGA1" /></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><input type="datetime-local" id="2entradaGA2" /></td>
                      <td><input type="datetime-local" id="2salidaGA2" /></td>
                      <td><input type="datetime-local" id="2pesadoGA2" /></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><input type="datetime-local" id="2entradaGA3" /></td>
                      <td><input type="datetime-local" id="2salidaGA3" /></td>
                      <td><input type="datetime-local" id="2pesadoGA3" /></td>
                    </tr>
                  </tbody>
                </table>
                <h4>3. Secado de Cartuchos</h4>
                <input type="datetime-local" id="fechaDefGA2">
                <button id="btnFechaDeFGA2">Apl F.</button>
                <hr />
                <table class="table">
                  <thead>
                    <th>Temperatura</th>
                    <th>Hora entrada</th>
                    <th>Hora salida</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="number" id="3temperaturaGA" /> °C</td>
                      <td><input type="datetime-local" id="3entradaGA" /></td>
                      <td><input type="datetime-local" id="3salidaGA" /></td>
                    </tr>
                  </tbody>
                </table>
                <h4>4. Tiempo de reflujo</h4>
                <hr />
                <table class="table">
                  <thead>
                    <tr>
                      <th>Hora entrada</th>
                      <th>Hora salida</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="datetime-local" id="4entradaGA" /></td>
                      <td><input type="datetime-local" id="4salidaGA" /></td>
                    </tr>
                  </tbody>
                </table>
                <h4>5. Enfriado de matraces</h4>
                <hr />
                <table class="table">
                  <thead>
                    <tr>
                      <th>Hora entrada</th>
                      <th>Hora salida</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="datetime-local" id="5entradaGA" /></td>
                      <td><input type="datetime-local" id="5salidaGA" /></td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>

          </div>
          <div class="tab-pane fade" id="tabVol" role="tabpanel" aria-labelledby="tabVol-tab">
            <div class="row">
              <div class="col-md-12">
                <button id="btnGuardarVal" class="btn btn-success">Guardar</button>
                <button id="btnEjecutarVal" class="btn btn-success">Ejecutar</button>
                <button id="btnLimpiarVal" class="btn btn-success">Limpiar</button>
              </div>
              <div class="col-md-12">
                <!-- ***************************** -->
                <!-- Inicio Dureza -->
                <!-- ***************************** -->
                <div class="row" id="secctionDureza" hidden>
                  <div class="col-md-6">
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
                        <tr onclick="habilitarTabla('tableDurezaBlanco','tableDurezaValoracion')">
                          <td>Blanco Análisis</td>
                          <td>A</td>
                          <td><input type="text" value="" id="blancoResDur" disabled></td>
                          <td>2</td>
                        </tr>
                        <tr onclick="habilitarTabla('tableDurezaValoracion','tableDurezaBlanco')">
                          <td>Factor real de dureza</td>
                          <td>((A/B) + (A/C) + (A/D))/3 </td>
                          <td><input type="text" value="" id="normalidadResDur" disabled></td>
                          <td>3</td>
                        </tr>
                      </tbody>
                    </table> 
                  </div>
                  <div class="col-md-6">
                    <table class="table" id="tableDurezaBlanco" hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>B1</td>
                          <td>Blanco 1</td>
                          <td><input type="text" id="blancoDureza"></td>
                        </tr>
                        <tr class="durSec3">
                          <td>B2</td>
                          <td>Blanco 2</td>
                          <td><input type="text" id="blancoDureza2"></td>
                        </tr>
                        <tr class="durSec3">
                          <td>B3</td>
                          <td>Blanco 3</td>
                          <td><input type="text" id="blancoDureza3"></td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table" id="tableDurezaValoracion" hidden>
                      <thead>
                        <tr>
                          <th>Parametro</th>
                          <th>Descripción</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="bg-success durSec1">
                          <td>A</td>
                          <td>mg CaCO3 EN LA SOLUCION TITULADOS</td>
                          <td><input type="number" id="tituladoDurSec1"></td>
                          <td>C</td>
                        </tr>
                        <tr class="bg-success durSec1">
                          <td>B</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur1Sec1"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-success durSec1">
                          <td>C</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur2Sec1"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-success durSec1">
                          <td>D</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur3Sec1"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-success durSec1">
                          <td>R</td>
                          <td>Factor Real de dureza 1</td>
                          <td><input type="number" id="resDurezaSec1"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-primary durSec2">
                          <td>A</td>
                          <td>mg CaCO3 EN LA SOLUCION TITULADOS</td>
                          <td><input type="number" id="tituladoDurSec2" style="color: black"></td>
                          <td>C</td>
                        </tr>
                        <tr class="bg-primary durSec2">
                          <td>B</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur1Sec2" style="color: black"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-primary durSec2">
                          <td>C</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur2Sec2" style="color: black"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-primary durSec2">
                          <td>D</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur3Sec2" style="color: black"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-primary durSec2">
                          <td>R</td>
                          <td>Factor Real de dureza 2</td>
                          <td><input type="number" id="resDurezaSec2" style="color: black"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-info durSec3">
                          <td>A</td>
                          <td>mg CaCO3 EN LA SOLUCION TITULADOS</td>
                          <td><input type="number" id="tituladoDurSec3"></td>
                          <td>C</td>
                        </tr>
                        <tr class="bg-info durSec3">
                          <td>B</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur1Sec3"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-info durSec3">
                          <td>C</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur2Sec3"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-info durSec3">
                          <td>D</td>
                          <td>mL DE LA DISOLUCION DE EDTA</td>
                          <td><input type="number" id="edtaDur3Sec3"></td>
                          <td>V</td>
                        </tr>
                        <tr class="bg-info durSec3">
                          <td>R</td>
                          <td>Factor Real de dureza 3</td>
                          <td><input type="number" id="resDurezaSec3"></td>
                          <td>V</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- ***************************** -->
                <!-- Fin Dureza -->
                <!-- ***************************** -->

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
                    <table class="table" id="tableCloroBlanco" hidden>
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
                          <td>
                            <div id="divTitulo1Cloros">mL Titulado 1 de Tiosulfato</div>
                            

                          </td>
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
                          <td>
                            
                            <div id="divTituloTrazableCloros">mL de K2Cr2O7 Trazable</div>
                            
                          </td>
                          <td><input type="number" id="trazableClo" value="10"></td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>Normalidad teorica</td>
                          <td><input type="number" id="normalidadClo" value="0.01"></td>
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
                    <table class="table" id="tableDqoBlanco" hidden>
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
                    <table class="table" id="tableNBlanco" hidden>
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
            </div>
          </div>
          {{-- DQO --}}
          <div class="tab-pane fade" id="tabDqo" role="tabpanel" aria-labelledby="dqo-tab">
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
              <div class="col-md-3">
                <h4>Soluble</h4>
                <select id="solubleDqo">
                  <option value="1">Si</option>
                  <option value="2">No</option>
                </select>
              </div>
              <div class="col-md-6">
                <button class="btn btn-sm btn-success" id="btnGuardarTipoDqo">Guardar</button>
              </div>
            </div>

            <hr>
<!-- 
            <h4>Ebullición</h4>
            <label>Lote ID: <input type="text" id="ebullicion_loteId"></label> <br>
            <label>Inicio: <input type="time" id="ebullicion_inicio"></label>
            <label>Fin: <input type="time" id="ebullicion_fin"></label><br><br>

            <p>Bureta utilizada para titulación</p>
            <label>INVLAB: <input type="text" id="ebullicion_invlab"></label> <br> -->
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
                <label>Cantidad de agua en dilución en ltros preparado: <input class="form-control" type="text" id="cantDilucion"></label>
              </div>
              <div class="col-md-6">
                <label for="">Tiempo de aireacion</label>
                <br>
                <label>De: <input class="form-control" type="time" id="de"></label>
                <label>A: <input class="form-control" type="time" id="a"></label>
              </div>
              <div class="col-md-4">
                <label>Diluciones preparadas el dia: <input class="form-control" type="text" id="pag"></label>
              </div>
              <div class="col-md-4">
                <label>Diluciones registradas en bit: <input class="form-control" type="text" id="n"></label>
              </div>
              <div class="col-md-4">
                <label>Estandares Preparado registrado en el folio: <input class="form-control" type="text" id="dilucion"></label>
              </div>
              <div class="col-md-4">
                <label>Estandare registdos en bit: <input class="form-control" type="text" id="estandaresbit"></label>
              </div>
              <div class="col-md-12">
                <button class="btn btn-successs" id="btnGuardarDqo">Guardar</button>
              </div>
            </div>
          </div>
          {{-- DBO FIN --}}

          
          <div class="tab-pane fade" id="tabAlcalinidad" role="tabpanel" aria-labelledby="tabAlcalinidad-tab">
            <div class="row"> 
                <div class="col-md-6" >
                  <!-- <button id="btnSetNormalidadAlc" class="btn-success"><i class="fas fa-save"></i> Crear</button> -->
                  <button id="btnSetNormalidadAlc" class="btn-info"><i class="fas fa-square-root-alt"></i> Ejecutar</button>
                  <table class="table" id="tableValAlcalinidad">
                      <tbody>
                        <tr>
                        <td>ID</td>
                        <td><input type="text" id="idNormalidadAlc"></td>
                        </tr>
                        <tr>
                          <td>Formula</td>
                          <td>NORMALIDAD ACIDO</td>
                        </tr>
                        <tr>
                          <td>Exp</td>
                          <td>((A)(D*H)*I+(B))</td>
                        </tr>
                        <tr>
                          <td>Resultado</td>
                          <td><input type="text" id="resValAlc" style="width: 50px"></td>
                        </tr>
                        <tr>
                          <td># Dec</td>
                          <td>3</td>
                        </tr>
                        <tr>
                          <td>Fecha Ini</td>
                          <td><input type="date" id="fecIniAlc"></td>
                        </tr>
                        <tr>
                          <td>Fecha Fin</td>
                          <td><input type="date" id="fecFinAlc"></td>
                        </tr> 
                      </tbody>
                  </table>
                  <div id="tableValAlcalinidadHist"></div>


                </div>
                <div class="col-md-6">
                  <table class="table">
                      <thead>
                        <tr>
                          <th>Variable</th>
                          <th>Descripcion</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>A</td>
                          <td>Gramos de carbonato 1</td>
                          <td><input type="text" id="granoCarbon1" style="width: 50px" value="0.0265"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>Gramos de carbonato 2</td>
                          <td><input type="text" id="granoCarbon2" style="width: 50px" value="0.0265"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>C</td>
                          <td>Gramos de carbonato 3</td>
                          <td><input type="text" id="granoCarbon3" style="width: 50px" value="0.0265"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>mL Titulados de H2</td>
                          <td><input type="text" id="tituladodeH1" style="width: 50px"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>E</td>
                          <td>mL Titulados de H2</td>
                          <td><input type="text" id="tituladodeH2" style="width: 50px"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>G</td>
                          <td>mL Titulados de H2</td>
                          <td><input type="text" id="tituladodeH3" style="width: 50px"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>H</td>
                          <td>Gramos equivalente</td>
                          <td><input type="text" id="equivalenteAlc" style="width: 50px" value="53"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>I</td>
                          <td>Factor de conversion</td>
                          <td><input type="text" id="factConversionAlc" style="width: 50px" value="1000"></td>
                          <td>C</td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>
          </div>

          <div class="tab-pane fade" id="tabAcidez" role="tabpanel" aria-labelledby="tabAcidez-tab">
            <div class="row"> 
                <div class="col-md-6" >
                  <!-- <button id="btnSetNormalidadAlc" class="btn-success"><i class="fas fa-save"></i> Crear</button> -->
                  <button id="btnSetNormalidadAci" class="btn-info"><i class="fas fa-square-root-alt"></i> Ejecutar</button>
                  <table class="table" id="tableAcidez">
                      <tbody>
                        <tr>
                        <td>ID</td>
                        <td><input type="text" id="idNormalidadAci"></td>
                        </tr>
                        <tr>
                          <td>Formula</td>
                          <td>NORMALIDAD HIDRÓXIDO DE SODIO</td>
                        </tr>
                        <tr>
                          <td>Exp</td>
                          <td>((A)(D*H)*I+(B))</td>
                        </tr>
                        <tr>
                          <td>Resultado</td>
                          <td><input type="text" id="resValAci" style="width: 50px"></td>
                        </tr>
                        <tr>
                          <td># Dec</td>
                          <td>3</td>
                        </tr>
                        <tr>
                          <td>Fecha Ini</td>
                          <td><input type="date" id="fecIniAci"></td>
                        </tr>
                        <tr>
                          <td>Fecha Fin</td>
                          <td><input type="date" id="fecFinAci"></td>
                        </tr> 
                      </tbody>
                  </table>
                  <div id="tableValAcidezHist"></div>


                </div>
                <div class="col-md-6">
                  <table class="table">
                      <thead>
                        <tr>
                          <th>Variable</th>
                          <th>Descripcion</th>
                          <th>Valor</th>
                          <th>Tipo</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>A</td>
                          <td>Gramos del biftalato de potasio 1</td>
                          <td><input type="text" id="granoBifAci1" style="width: 50px" value="0.102"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>Gramos del biftalato de potasio 2</td>
                          <td><input type="text" id="granoBifAci2" style="width: 50px" value="0.102"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>C</td>
                          <td>Gramos del biftalato de potasio 3</td>
                          <td><input type="text" id="granoBifAci3" style="width: 50px" value="0.102"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>mL De NaOH Utilizados (1)</td>
                          <td><input type="text" id="tituladoAci1" style="width: 50px"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>E</td>
                          <td>mL De NaOH Utilizados (2)</td>
                          <td><input type="text" id="tituladoAci2" style="width: 50px"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>G</td>
                          <td>mL De NaOH Utilizados (3)</td>
                          <td><input type="text" id="tituladoAci3" style="width: 50px"></td>
                          <td>V</td>
                        </tr>
                        <tr>
                          <td>H</td>
                          <td>Gramos equivalente</td>
                          <td><input type="text" id="equivalenteAci" style="width: 50px" value="53"></td>
                          <td>C</td>
                        </tr>
                        <tr>
                          <td>I</td>
                          <td>Factor de conversion</td>
                          <td><input type="text" id="factConversionAci" style="width: 50px" value="1000"></td>
                          <td>C</td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>
          </div>

          <div class="tab-pane fade" id="tabPlantillas" role="tabpanel" aria-labelledby="tabPlantillas-tab">
            <div class="row">
              <div class="col-md-12">
                <button id="btnBitacora" class="btn-success"><i class="fas fa-save"></i> Guardar</button>
              </div>
              <div class="col-md-12">
                <input type="text" id="tituloBit" hidden>
                <div id="divSummer"></div>
                <input type="text" id="revBit" hidden>
              </div>
            </div>
          </div>
        </div>
        {{-- Fin de body --}}
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

{{-- Fin modal Detalle Lote --}}

{{--? Inicio modal control calidad --}}

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
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Tipo</label>
              <select class="form-control" id="controlCalidad">
              
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" id="btnSetControl" class="btn btn-primary">Generar</button>
      </div>

    </div>
  </div>
</div>

{{--todo Fin modal control calidad --}}

{{--* Inicio Modal Default --}}
<div class="modal fade" id="modalDirectoDef" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo Default</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirectoDef"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirectoDef')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDirectoDef" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripcion</th>
                  <th>Valor</th>

                </tr>
              </thead>
              <tbody>

                <tr>
                  <td>R</td>
                  <td>Resultado Directo</td>
                  <td><input type="text" id="resDirectoDef"></td>

                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Default --}}

{{--todo Fin modal MB --}}
{{--! FIN Modal de capturas de parametros --}}

   <!-- Modal Control Calidad-->
   <div class="modal fade" id="modalHistorial" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Historial</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="divHistorial">

                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

<!-- The Modal --> 
<div class="modal fade" id="modalImgFoto" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width: 80%">
                    <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="">Foto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div id="divImagen">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

 <!-- Spinner -->
 <div id="spinner"></div>
            @endsection



@section('javascript')
<script src="{{asset('/public/js/alimentos/captura.jsx')}}?v=1.0.0"></script>

<script src="{{asset('/assets/summer/summernote.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@stop