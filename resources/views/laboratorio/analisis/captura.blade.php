@extends('voyager::master')
@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
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
                @foreach ($control as $item)
                <option value="{{$item->Id_control}}">{{$item->Control}}</option>
                @endforeach
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

{{--! INICIO Modal de capturas de parametros --}}

{{--todo Inicio COT --}}
<div class="modal fade" id="modalCapturaCOT" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Captura de resultados COT</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionCOT" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionCOT')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
            <div class="col-md-2">
              <label id="ph">pH</label>
              <input type="text" disabled class="form-control" id="phCampo">
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary" id="btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>

          <div class="col-md-8">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoCOT" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
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
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>ABS</td>
                  <td>ABS Promedio</td>
                  <td><input type="text" id="abs1COT"></td>
                  <td><input type="text" id="abs2COT"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>CA</td>
                  <td>Blanco</td>
                  <td><input name="campos" type="number" id="blanco1COT"></td>
                  <td><input name="campos" type="number" id="blanco2COT"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CB</td>
                  <td>b</td>
                  <td><input name="campos" type="number" id="b1COT"></td>
                  <td><input name="campos" type="number" id="b2COT"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CM</td>
                  <td>m</td>
                  <td><input name="campos" type="number" id="m1COT"></td>
                  <td><input name="campos" type="number" id="m2COT"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CR</td>
                  <td>r</td>
                  <td><input name="campos" type="number" id="r1COT"></td>
                  <td><input name="campos" type="number" id="r2COT"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Factor dilucion</td>
                  <td><input type="number" id="fDilucion1COT" disabled></td>
                  <td><input type="number" id="fDilucion2COT" disabled></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Vol de la muestra</td>
                  <td><input name="campos" type="number" id="volMuestra1COT"></td>
                  <td><input name="campos" type="number" id="volMuestra2COT"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>X</td>
                  <td>Absorbancia1</td>
                  <td><input name="campos" type="number" id="abs11COT"></td>
                  <td><input name="campos" type="number" id="abs12COT"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Y</td>
                  <td>Absorbancia2</td>
                  <td><input name="campos" type="number" id="abs21COT"></td>
                  <td><input name="campos" type="number" id="abs22COT"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Z</td>
                  <td>Absorbancia3</td>
                  <td><input name="campos" type="number" id="abs31COT"></td>
                  <td><input name="campos" type="number" id="abs32COT"></td>
                  <td>V</td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCapturaEspectro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Captura de resultados espectro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionEspectro" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" onclick="setObservacion('observacionEspectro')"><i
                  class="voyager-play"></i> Aplicar</button>
            </div>
            <div class="col-md-2">
              <label id="ph">pH</label>
              <input type="text" disabled class="form-control" id="phCampo">
            </div>
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          {{-- <div class="col-md-2">
            <button class="btn btn-warning">Liberar</button>
          </div> --}}
          <div class="col-md-8">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoEspectro" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
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
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>ABS</td>
                  <td>ABS Promedio</td>
                  <td><input type="text" id="absPromEspectro1"></td>
                  <td><input type="text" id="absPromEspectro2"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>CA</td>
                  <td>Blanco</td>
                  <td><input name="campos" type="number" id="blancoEspectro1"></td>
                  <td><input name="campos" type="number" id="blancoEspectro2"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CB</td>
                  <td>b</td>
                  <td><input name="campos" type="number" id="bEspectro1" disabled></td>
                  <td><input name="campos" type="number" id="bEspectro2" disabled></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CM</td>
                  <td>m</td>
                  <td><input name="campos" type="number" id="mEspectro1" disabled></td>
                  <td><input name="campos" type="number" id="mEspectro2" disabled></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CR</td>
                  <td>r</td>
                  <td><input name="campos" type="number" id="rEspectro1" disabled></td>
                  <td><input name="campos" type="number" id="rEspectro2" disabled></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Factor dilucion</td>
                  <td><input type="number" id="fDilucionEspectro1" disabled></td>
                  <td><input type="number" id="fDilucionEspectro2" disabled></td>
                  <td>V</td>
                </tr>
                <tr id="conPh">
                  <td>P</td>
                  <td>pH Final</td>
                  <td><input name="campos" type="number" id="phFinEspectro1"></td>
                  <td><input name="campos" type="number" id="phFinEspectro2"></td>
                  <td>V</td>
                </tr>
                <tr id="conPh2">
                  <td>P2</td>
                  <td>pH Inicial</td>
                  <td><input name="campos" type="number" id="phIniEspectro1" value="0"></td>
                  <td><input name="campos" type="number" id="phIniEspectro2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr id="conN1">
                  <td>N1</td>
                  <td>Nitratos</td>
                  <td><input name="campos" type="number" id="nitratosEspectro1" value="0"></td>
                  <td><input name="campos" type="number" id="nitratosEspectro2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr id="conN2">
                  <td>N2</td>
                  <td>Nitritos</td>
                  <td><input name="campos" type="number" id="nitritosEspectro1" value="0"></td>
                  <td><input name="campos" type="number" id="nitritosEspectro2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr id="conN3">
                  <td>N3</td>
                  <td>Sulfuros</td>
                  <td><input name="campos" type="number" id="sulfurosEspectro1" value="0"></td>
                  <td><input name="campos" type="number" id="sulfurosEspectro2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Vol de la muestra</td>
                  <td><input name="campos" type="number" id="volMuestraEspectro1"></td>
                  <td><input name="campos" type="number" id="volMuestraEspectro2"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>X</td>
                  <td>Absorbancia1</td>
                  <td><input name="campos" type="number" id="abs1Espectro1"></td>
                  <td><input name="campos" type="number" id="abs1Espectro2"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Y</td>
                  <td>Absorbancia2</td>
                  <td><input name="campos" type="number" id="abs2Espectro1"></td>
                  <td><input name="campos" type="number" id="abs2Espectro2"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Z</td>
                  <td>Absorbancia3</td>
                  <td><input name="campos" type="number" id="abs3Espectro1"></td>
                  <td><input name="campos" type="number" id="abs3Espectro2"></td>
                  <td>V</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
{{--todo Fin COT --}}

{{--todo Inicio Sulfatos --}}

<div class="modal fade" id="modalCapturaSulfatos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Captura de resultados Sulfatos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionSulfatos" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionSulfatos')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div>

            <div class="col-md-2">
              <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
                Ejecutar</button>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="resultado">Resultado</label>
                <input type="text" id="resultadoSulfatos" style="font-size: 20px;color:red;" placeholder="Resultado">
              </div>
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
                    <th>Tipo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>ABS</td>
                    <td>ABS Promedio</td>
                    <td><input type="text" id="abs1SulfatosF"></td>
                    <td><input type="text" id="abs2SulfatosF"></td>
                    <td>C</td>
                  </tr>
                  <tr>
                    <td>CA</td>
                    <td>Blanco</td>
                    <td><input name="campos" type="number" id="blancoSulfatos1F"></td>
                    <td><input name="campos" type="number" id="blancoSulfatos2F"></td>
                    <td>F</td>
                  </tr>
                  <tr>
                    <td>CB</td>
                    <td>b</td>
                    <td><input name="campos" type="number" id="b1SulfatosF" disabled></td>
                    <td><input name="campos" type="number" id="b2SulfatosF"></td>
                  </tr>
                  <tr>
                    <td>CM</td>
                    <td>m</td>
                    <td><input name="campos" type="number" id="m1SulfatosF" disabled></td>
                    <td><input name="campos" type="number" id="m2SulfatosF"></td>
                  </tr>
                  <tr>
                    <td>CR</td>
                    <td>r</td>
                    <td><input name="campos" type="number" id="r1SulfatosF" disabled></td>
                    <td><input name="campos" type="number" id="r2SulfatosF"></td>
                  </tr>
                  <tr>
                    <td>D</td>
                    <td>Factor dilucion</td>
                    <td><input type="number" id="fDilucion1SulfatosF" disabled></td>
                    <td><input type="number" id="fDilucion2SulfatosF" disabled></td>
                    <td>V</td>
                  </tr>

                  <tr>
                    <td>E</td>
                    <td>Vol de la muestra</td>
                    <td><input name="campos" type="number" id="volMuestra1SulfatosF" value="0"></td>
                    <td><input name="campos" type="number" id="volMuestra2SulfatosF" value="0"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS1</td>
                    <td>Absorbancia1</td>
                    <td><input name="campos" type="number" id="abs11SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs12SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS2</td>
                    <td>Absorbancia2</td>
                    <td><input name="campos" type="number" id="abs21SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs22SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS3</td>
                    <td>Absorbancia3</td>
                    <td><input name="campos" type="number" id="abs31SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs32SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS4</td>
                    <td>Absorbancia4</td>
                    <td><input name="campos" type="number" id="abs41SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs42SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS5</td>
                    <td>Absorbancia5 </td>
                    <td><input name="campos" type="number" id="abs51SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs52SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS6</td>
                    <td>Absorbancia6</td>
                    <td><input name="campos" type="number" id="abs61SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs62SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS7</td>
                    <td>Absorbancia7</td>
                    <td><input name="campos" type="number" id="abs71SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs72SulfatosF"></td>
                    <td>V</td>
                  </tr>
                  <tr>
                    <td>ABS8</td>
                    <td>Absorbancia8</td>
                    <td><input name="campos" type="number" id="abs81SulfatosF"></td>
                    <td><input name="campos" type="number" id="abs82SulfatosF"></td>
                    <td>V</td>
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

{{--todo Fin Sulfatos --}}

{{--todo Inicio GA --}}

<div class="modal fade" id="modalCapturaGA" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados GA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionGA" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionGA')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoGA" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-5">
            <input type="text" id="pGA" style="font-size: 20px;color:blue;" placeholder="No. Serie Matraz">
          </div>
          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>H</td>
                  <td>Masa Final</td>
                  <td><input type="text" id="hGA1" value="0"></td>
                  <td><input type="text" id="hGA2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>J</td>
                  <td>Masa Inicial 1</td>
                  <td><input type="text" id="jGA1" value="0"></td>
                  <td><input type="text" id="jGA2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>K</td>
                  <td>Masa Inicial 2</td>
                  <td><input type="text" id="kGA1" value="0"></td>
                  <td><input type="text" id="kGA2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Masa Inicial 3</td>
                  <td><input type="text" id="cGA1" value="0"></td>
                  <td><input type="text" id="cGA2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>L</td>
                  <td>Ph</td>
                  <td><input type="text" id="lGA1" value="0"></td>
                  <td><input type="text" id="lGA2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>I</td>
                  <td>Volumen</td>
                  <td><input type="text" id="iGA1" value="0"></td>
                  <td><input type="text" id="iGA2" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>G</td>
                  <td>Blanco</td>
                  <td><input type="text" id="gGA1" value="0"></td>
                  <td><input type="text" id="gGA2" value="0"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Factor de conversión</td>
                  <td><input type="text" id="eGA1" value="1000000"></td>
                  <td><input type="text" id="eGA2" value="1000000"></td>
                  <td>C</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{--todo Fin GA --}}

{{--todo Inicio Modal Solidos --}}

{{--* Inicio modal directo --}}
<div class="modal fade" id="modalCapturaSolidosDir" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Solidos (Diferencia) - Folio de muestra: <input type="text" id="folioSolidosDir" style="border: none; color:red;" disabled></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionSolidosDir"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionSolidosDir')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoSolidosDir" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Inmhoff</label>
                  <input type="text" class="form-control" id="inmhoffSolidosDir">
                </div>
                <div class="form-group">
                  <label for="">Resultado</label>
                  <input type="text" class="form-control" id="resultadoModalSolidosDir">
                </div>
                <div class="form-group">
                  <label for="">Temperatura de la muestra llegada</label>
                  <input type="text" class="form-control" id="temperaturaLlegadaSolidosDir">
                </div>
                <div class="form-group">
                  <label for="">Temperatura de la muestra al analizar</label>
                  <input type="text" class="form-control" id="temperaturaAnalizadaSolidosDir">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin modal directo --}}

{{--* Inicio modal por diferencia --}}
<div class="modal fade" id="modalCapturaSolidosDif" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Solidos (Diferencia)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionSolidosDif"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionSolidosDif')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoSolidosDif" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-3">
            <label for="preResDif">Pres resultado</label>
            <input type="text" id="preResDifSolidosDif" style="font-size: 20px;color:blue;"
              placeholder="No. Serie Crisol">
          </div>
          <div class="col-md-3">
            <label for="preResDif">Conductividad</label>
            <input type="text" id="conductividadDifSolidosDif" style="font-size: 20px;color:rgb(0, 117, 78);"
              placeholder="conductividad">
          </div>
          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="text" id="nomParametro1SolidosDif"></td>
                  <td><input type="text" id="val11SolidosDif" value="0"></td>
                  <td><input type="text" id="val12SolidosDif" value="0"></td>
                </tr>
                <tr>
                  <td><input type="text" id="nomParametro2SolidosDif"></td>
                  <td><input type="text" id="val21SolidosDif" value="0"></td>
                  <td><input type="text" id="val22SolidosDif" value="0"></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin modal por diferencia --}}

{{--* Inicio modal default --}}.
<div class="modal fade" id="modalCapturaSolidos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Solidos | # Muestra: <input type="text" id="muestraCapturaSolidos" style="width: 200px;border: none"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionSolidos" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionSolidos')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>
          <div class="col-md-1">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="resultado">Resultado</label>
                  <input type="text" id="resultadoSolidos" style="font-size: 20px;color:red;" placeholder="Resultado">
                </div>
            </div>

            <div class="col-md-3">
              <div id="tituloCrisol">Crisol</div>
              <input type="text" id="crisolSolidos" style="font-size: 20px;color:blue;" placeholder="No. Serie Matraz">
            </div>

            <div class="col-md-3">
              <label for="conductividad">Conductividad</label>
              <input type="text" id="conducSolidosDef" style="font-size: 20px;color:rgb(0, 117, 78);"
                placeholder="conductividad">
            </div>
              </div>
          </div>
          
          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  {{-- <th>Parametro</th> --}}
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  {{-- <th>Tipo</th> --}}
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div id="titulomasa1Solidos">Masa 6</div>
                  </td>
                  <td><input name="campos" type="text" id="m11Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="m12Solidos" value="0"></td>
                </tr>
                <tr>
                  <td>
                    <div id="titulomasa2Solidos">Masa 2</div>
                  </td>
                  <td><input name="campos" type="text" id="m21Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="m22Solidos" value="0"></td>
                </tr>
                <tr>
                  <td id="pscmS1">Peso constante c/muestra 1 A</td>
                  <td><input name="campos" type="text" id="pcm11Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pcm12Solidos" value="0"></td>
                </tr>
                <tr>
                  <td id="pscmS2">Peso constante c/muestra 2 A</td>
                  <td><input name="campos" type="text" id="pcm21Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pcm22Solidos" value="0"></td>
                </tr>
                <tr>
                  <td id="pcS1">Peso constante 1 B</td>
                  <td><input name="campos" type="text" id="pc1Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pc2Solidos" value="0"></td>
                </tr>
                <tr>
                  <td id="pcS2">Peso constante 2 B</td>
                  <td><input name="campos" type="text" id="pc21Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pc22Solidos" value="0"></td>
                </tr>
                <tr>
                  <td>Volumen de muestra</td>
                  <td><input name="campos" type="text" id="v1Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="v2Solidos" value="0"></td>
                </tr>
                <tr>
                  <td>Factor de conversión</td>
                  <td><input name="campos" type="text" id="f1Solidos" value="1000000"></td>
                  <td><input name="campos" type="text" id="f2Solidos" value="1000000"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin modal default --}}
{{--todo Fin Modal Solidos --}}

{{--todo Inicio Modal Volumetria --}}
{{--* Inicio Modal Nitrogeno --}}
<div class="modal fade" id="modalCapturaNitrogenoVol" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Nitrogeno</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionNitrogenoVol"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionNitrogenoVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoNitrogenoVol" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A</td>
                  <td>Mililitros Titulados Muestra</td>
                  <td><input type="text" id="tituladosNitro1Vol" value="0"></td>
                  <td><input type="text" id="tituladosNitro2Vol" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Mililitros titulados del blanco</td>
                  <td><input type="text" id="blancoNitro1Vol" value="0"></td>
                  <td><input type="text" id="blancoNitro2Vol" value="0"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Molaridad del FAS</td>
                  <td><input type="text" id="molaridadNitro1Vol" value="0"></td>
                  <td><input type="text" id="molaridadNitro2Vol" value="0"></td>
                  <td>F</td>
                </tr>

                <tr>
                  <td>D</td>
                  <td>Factor de quivalencia</td>
                  <td><input type="text" id="factorNitro1Vol" value="2"></td>
                  <td><input type="text" id="factorNitro2Vol" value="2"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Factor de conversión</td>
                  <td><input type="text" id="conversion1Vol" value="14000"></td>
                  <td><input type="text" id="conversion2Vol" value="14000"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>G</td>
                  <td>Volumen de muestra</td>
                  <td><input type="text" id="volNitro1Vol" value="0"></td>
                  <td><input type="text" id="volNitro2Vol" value="0"></td>
                  <td>V</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Nitrogeno --}}

{{--* Inicio Modal Alcalinidad --}}
<div class="modal fade" id="modalCapturaAlcalinidadVol" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=""><div id="tituloModalAlcalinidad">Captura de resultados Alcalinida</div></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionAlcalinidadVol"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionAlcalinidadVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoAlcalinidad" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A</td>
                  <td>Mililitros Titulados Muestra</td>
                  <td><input type="text" id="tituladoAlc1Vol" value="0"></td>
                  <td><input type="text" id="tituladoAlc2Vol" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Ph Muestra</td>
                  <td><input type="text" id="phMuestraAlc1Vol" value="0"></td>
                  <td><input type="text" id="phMuestraAlc2Vol" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Volumen Total de muestra</td>
                  <td><input type="text" id="volMuestraAlc1Vol" value="0"></td>
                  <td><input type="text" id="volMuestraAlc2Vol" value="0"></td>
                  <td>V</td>
                </tr>

                <tr>
                  <td>B</td>
                  <td><div id="tituloNormalidadModal">Normalidad aido sulfúrico</div></td>
                  <td><input type="text" id="normalidadAlc1Vol" value="2"></td>
                  <td><input type="text" id="normalidadAlc2Vol" value="2"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Factor de conversión</td>
                  <td><input type="text" id="conversionAlc1Vol" value="50000"></td>
                  <td><input type="text" id="conversionAlc2Vol" value="50000"></td>
                  <td>C</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Alcalinidad --}}

{{--* Inicio Modal Alcalinidad Total--}}
<div class="modal fade" id="modalCapturaAlcalinidadToVol" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Alcalinida Total</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionAlcalinidadToVol"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionAlcalinidadToVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoAlcalinidadTo" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
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
                  <td>Alc. A la Fenoftaleina</td>
                  <td><input type="text" id="resFeno" value="0" ></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Alc. A la Anaranjado</td>
                  <td><input type="text" id="resAnaranjado" value="0" ></td>
                  <td>F</td>
                </tr>
         
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Alcalinidad Total --}}

{{--* Inicio Modal Nitrogeno Equipo --}}
<div class="modal fade" id="modalCapturaNitrogenoEVol" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Nitrogeno</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionNitrogenoEVol"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionNitrogenoEVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoNitrogenoEVol" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A</td>
                  <td>factor de dilución</td>
                  <td><input type="text" id="factor1ENitrogenoEVol" value="0"></td>
                  <td><input type="text" id="factor2NitrogenoEVol" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Concentración de NH3 en mg/L</td>
                  <td><input type="text" id="concentracion1ENitrogenoEVol" value="0"></td>
                  <td><input type="text" id="concentracion2NitrogenoEVol" value="0"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Volumen Añadido al std</td>
                  <td><input type="text" id="volAñadidoStd1ENitrogenoEVol" value="0"></td>
                  <td><input type="text" id="volAñadidoStd2NitrogenoEVol" value="0"></td>
                  <td>F</td>
                </tr>

                <tr>
                  <td>D</td>
                  <td>Volumen Añadido a Muestra</td>
                  <td><input type="text" id="VolAñadidoMuestra1ENitrogenoEVol" value="0"></td>
                  <td><input type="text" id="VolAñadidoMuestra2NitrogenoEVol" value="0"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Volumen de la Muestra en mL</td>
                  <td><input type="text" id="volumenMuestraE1NitrogenoEVol" value="100"></td>
                  <td><input type="text" id="volumenMuestraE2NitrogenoEVol" value="100"></td>
                  <td>C</td>
                </tr>


              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Nitrogeno Equipo --}}

{{--* Inicio Modal Cloro --}}
<div class="modal fade" id="modalCloroVol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Cloro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionCloroVol" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionCloroVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoCloroVol" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

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
              <tbody>
                <tr>
                  <td>A</td>
                  <td>MILILITROS TITULADOS DE MUESTRA</td>
                  <td><input type="text" id="cloroA1Vol" value="0"></td>
                  <td><input type="text" id="cloroA2Vol" value="0"></td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>mL DE MUESTRA</td>
                  <td><input type="text" id="cloroE1Vol" value="0"></td>
                  <td><input type="text" id="cloroE2Vol" value="0"></td>
                </tr>
                <tr>
                  <td>H</td>
                  <td>pH FINAL</td>
                  <td><input type="text" id="cloroH1Vol" value="0"></td>
                  <td><input type="text" id="cloroH2Vol" value="0"></td>
                </tr>
                <tr>
                  <td>G</td>
                  <td>pH Inicial</td>
                  <td><input type="text" id="cloroG1Vol" value="0"></td>
                  <td><input type="text" id="cloroG2Vol" value="0"></td>
                </tr>
                <tr>
                  <td>B</td>
                  <td>BLANCO DE ANALISIS</td>
                  <td><input type="text" id="cloroB1Vol" value="0"></td>
                  <td><input type="text" id="cloroB2Vol" value="0"></td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>NORMALIDAD REAL</td>
                  <td><input type="text" id="cloroC1Vol" value="0"></td>
                  <td><input type="text" id="cloroC2Vol" value="0"></td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>FACTOR DE CONVERSION mg/L</td>
                  <td><input type="text" id="cloroD1Vol" value="35450"></td>
                  <td><input type="text" id="cloroD2Vol" value="35450"></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Cloro --}}

{{--* Inicio Modal Dbo --}}
<div class="modal fade" id="modalDqoVol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Dbo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDqoVol" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDqoVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDqoVol" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo-</th>
                </tr>
              </thead>
              <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
              <tbody>
                <tr>
                  <td>B</td>
                  <td>Mililitros Titulados Muestra</td>
                  <td><input type="text" id="tituladoDqo1DqoVol" value="0"></td>
                  <td><input type="text" id="tituladoDqo2DqoVol" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Molaridad del FAS</td>
                  <td><input type="text" id="MolaridadDqo1DqoVol" value="0" disabled></td>
                  <td><input type="text" id="MolaridadDqo2DqoVol" value="0"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CA</td>
                  <td>Mililitros titulados del blanco</td>
                  <td><input type="text" id="blancoDqo1DqoVol" value="0" disabled></td>
                  <td><input type="text" id="blancoDqo2DqoVol" value="0"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Factor de quivalencia</td>
                  <td><input type="text" id="factorDqo1DqoVol" value="8000"></td>
                  <td><input type="text" id="factorDqo2DqoVol" value="8000"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Volumen de muestra</td>
                  <td><input type="text" id="volDqo1DqoVol" value="0"></td>
                  <td><input type="text" id="volDqo2DqoVol" value="0"></td>
                  <td>V</td>
                </tr>


              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Dbo --}}
{{--* Inicio Modal Dbo Espectro --}}
<div class="modal fade" id="modalDboEVol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Dbo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDboEVol" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDboEVol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDboEVol" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>ABS</td>
                  <td>ABS Promedio</td>
                  <td><input type="text" id="abs1DboEVol"></td>
                  <td><input type="text" id="abs2DboEVol"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>CA</td>
                  <td>Blanco</td>
                  <td><input name="campos" type="number" id="blanco1DboEVol"></td>
                  <td><input name="campos" type="number" id="blanco2DboEVol"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CB</td>
                  <td>b</td>
                  <td><input name="campos" type="number" id="b1DboEVol" disabled></td>
                  <td><input name="campos" type="number" id="b2DboEVol"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CM</td>
                  <td>m</td>
                  <td><input name="campos" type="number" id="m1DboEVol" disabled></td>
                  <td><input name="campos" type="number" id="m2DboEVol"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>CR</td>
                  <td>r</td>
                  <td><input name="campos" type="number" id="r1DboEVol" disabled></td>
                  <td><input name="campos" type="number" id="r2DboEVol"></td>
                  <td>F</td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Factor dilucion</td>
                  <td><input type="number" id="fDilucion1DboEVol" disabled></td>
                  <td><input type="number" id="fDilucion2DboEVol" disabled></td>
                  <td>V</td>
                </tr>

                <tr>
                  <td>E</td>
                  <td>Vol de la muestra</td>
                  <td><input name="campos" type="number" id="volMuestra1DboEVol"></td>
                  <td><input name="campos" type="number" id="volMuestra2DboEVol"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>X</td>
                  <td>Absorbancia1</td>
                  <td><input name="campos" type="number" id="abs11DboEVol"></td>
                  <td><input name="campos" type="number" id="abs12DboEVol"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Y</td>
                  <td>Absorbancia2</td>
                  <td><input name="campos" type="number" id="abs21DboEVol"></td>
                  <td><input name="campos" type="number" id="abs22DboEVol"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Z</td>
                  <td>Absorbancia3</td>
                  <td><input name="campos" type="number" id="abs31DboEVol"></td>
                  <td><input name="campos" type="number" id="abs32DboEVol"></td>
                  <td>V</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Dbo Espectro--}}
{{--todo Fin Modal Volumetria --}}

{{--todo Inicio Modal Directos --}}
{{--* Inicio Modal Directo --}}
<div class="modal fade" id="modalDirecto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirecto" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirecto')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" id="resultadoDirecto" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" id="phCampoCompuesto" style="font-size: 20px;" placeholder="ph campo">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Valor</th>
                  <th>Valor2</th>

                </tr>
              </thead>
              <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
              <tbody>

                <tr>
                  <td>L1.</td>
                  <td><input type="text" id="lecturaUno1Directo" value="0"></td>
                  <td><input type="text" id="lecturaUno2Directo" value="0"></td>

                </tr>
                <tr>
                  <td>L2.</td>
                  <td><input type="text" id="lecturaDos1Directo" value="0"></td>
                  <td><input type="text" id="lecturaDos2Directo" value="0"></td>

                </tr>
                <tr>
                  <td>L3.</td>
                  <td><input type="text" id="lecturaTres1Directo" value="0"></td>
                  <td><input type="text" id="lecturaTres2Directo" value="0"></td>

                </tr>
                <tr>
                  <td>Temperatura.</td>
                  <td><input type="text" id="temperatura1Directo" value="0"></td>
                  <td><input type="text" id="temperatura2Directo" value="0"></td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Directo --}}
{{--* Inicio Modal Turbiedad --}}
<div class="modal fade" id="modalDirectoTur" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirectoTur"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirectoTur')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDirectoTur" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>

                  <th>Valor</th>
                  <th>Valor2</th>

                </tr>
              </thead>
              <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
              <tbody>
                <tr>
                  <td>Factor de Dilución.</td>
                  <td><input type="text" id="dilusionTurb1DirectoTur" value="1"></td>
                  <td><input type="text" id="dilusionTurb2DirectoTur" value="1"></td>

                </tr>
                <tr>
                  <td>Volumen de Muestra.</td>
                  <td><input type="text" id="valumenTurb1DirectoTur" value="0"></td>
                  <td><input type="text" id="volumenTurb2DirectoTur" value="0"></td>

                </tr>
                <tr>
                  <td>L1.</td>
                  <td><input type="text" id="lecturaUnoTurb1DirectoTur" value="0"></td>
                  <td><input type="text" id="lecturaUnoTurb2DirectoTur" value="0"></td>

                </tr>
                <tr>
                  <td>L2.</td>
                  <td><input type="text" id="lecturaDosTurb1DirectoTur" value="0"></td>
                  <td><input type="text" id="lecturaDosTurb2DirectoTur" value="0"></td>

                </tr>
                <tr>
                  <td>L3.</td>
                  <td><input type="text" id="lecturaTresTurb1DirectoTur" value="0"></td>
                  <td><input type="text" id="lecturaTresTurb2DirectoTur" value="0"></td>

                </tr>
                <tr>
                  <td>Promedio.</td>
                  <td><input type="text" id="promedioTurb1DirectoTur" value="0"></td>
                  <td><input type="text" id="promedioTurb2DirectoTur" value="0"></td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Turbiedad --}}
{{--* Inicio Modal Cloruros --}}
<div class="modal fade" id="modalDirectoClo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo Cloruros</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirectoClo"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirectoClo')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDirectoClo" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>

                  <th>Valor</th>
                  <th>Valor2</th>

                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Fact. Dilución.</td>
                  <td><input type="text" id="dilucionCloro1DirectoClo" value="0"></td>
                  <td><input type="text" id="dilucionCloro2DirectoClo" value="0"></td>

                </tr>
                <tr>
                  <td>Volumen de Muestra.</td>
                  <td><input type="text" id="volumenCloro1DirectoClo" value="0"></td>
                  <td><input type="text" id="volumenCloro2DirectoClo" value="0"></td>

                </tr>
                <tr>
                  <td>L1.</td>
                  <td><input type="text" id="lecturaUnoCloro1DirectoClo" value="0"></td>
                  <td><input type="text" id="lecturaUnoCloro2DirectoClo" value="0"></td>

                </tr>
                <tr>
                  <td>L2.</td>
                  <td><input type="text" id="lecturaDosCloro1DirectoClo" value="0"></td>
                  <td><input type="text" id="lecturaDosCloro2DirectoClo" value="0"></td>

                </tr>
                <tr>
                  <td>L3.</td>
                  <td><input type="text" id="lecturaTresCloro1DirectoClo" value="0"></td>
                  <td><input type="text" id="lecturaTresCloro2DirectoClo" value="0"></td>

                </tr>
                <tr>
                  <td>Promedio.</td>
                  <td><input type="text" id="promedioCloro1DirectoClo" value="0"></td>
                  <td><input type="text" id="promedioCloro2DirectoClo" value="0"></td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Cloruros --}}

{{--* Inicio Modal Temperatura --}}
<div class="modal fade" id="modalDirectoTemp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo Temperatura</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirectoTemp"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirectoTemp')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDirectoTemp" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>

                  <th>Valor</th>
                  <th>Valor2</th>

                </tr>
              </thead>
              <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
              <tbody>

                <tr>
                  <td>L1.</td>
                  <td><input type="text" id="lecturaUno1TDirectoTemp" value="0"></td>
                  <td><input type="text" id="lecturaUno2TDirectoTemp" value="0"></td>

                </tr>
                <tr>
                  <td>L2.</td>
                  <td><input type="text" id="lecturaDos1TDirectoTemp" value="0"></td>
                  <td><input type="text" id="lecturaDos2TDirectoTemp" value="0"></td>

                </tr>
                <tr>
                  <td>L3.</td>
                  <td><input type="text" id="lecturaTres1TDirectoTemp" value="0"></td>
                  <td><input type="text" id="lecturaTres2TDirectoTemp" value="0"></td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Temperatura --}}


{{--* Inicio Modal Color --}}
<div class="modal fade" id="modalDirectoColor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo Color</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirectoColor"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirectoColor')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDirectoColor" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>

                  <th>Valor</th>
                  <th>Valor2</th>

                </tr>
              </thead>
              <tbody>

                <tr>
                  <td>A.</td>
                  <td>Color aparente.</td>
                  <td><input type="text" id="aparente1DirectoColor"></td>
                  <td><input type="text" id=""></td>

                </tr>
                <tr>
                  <td>B.</td>
                  <td>Color verdadero.</td>
                  <td><input type="text" id="verdadero1DirectoColor"></td>
                  <td><input type="text" id=""></td>

                </tr>
                <tr>
                  <td>C.</td>
                  <td>Factor dilución.</td>
                  <td><input type="text" id="dilusion1DirectoColor"></td>
                  <td><input type="text" id=""></td>

                </tr>
                <tr>
                  <td>D.</td>
                  <td>Volumen muestra.</td>
                  <td><input type="text" id="volumen1DirectoColor"></td>
                  <td><input type="text" id=""></td>

                </tr>
                <tr>
                  <td>E.</td>
                  <td>ph.</td>
                  <td><input type="text" id="ph1DirectoColor"></td>
                  <td><input type="text" id=""></td>

                </tr>
                <tr>
                  <td>G.</td>
                  <td>Facor de correción.</td>
                  <td><input type="text" id="factor1DirectoColor"></td>
                  <td><input type="text" id=""></td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Color --}}

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
{{--todo Fin Modal Directos --}}

{{--todo Inicio Modal Potable --}}
{{-- Inicio modal Dureza --}}
<div class="modal fade" id="modalDureza" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Dureza</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDureza" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDureza')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDureza" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor1</th>
                  <th class="durSec2">Valor2</th>
                  <th class="durSec2">Valor3</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td>A</td>
                  <td>Mililitros titulados de EDTA</td>
                  <td><input type="text" id="edta1Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="edta2Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="edta3Dureza" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>pH de la muestra</td>
                  <td><input type="text" id="ph1Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="ph2Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="ph3Dureza" value="0"></td>
                  <td>V</td>

                </tr>
                <tr>
                  <td>D</td>
                  <td>Volmuen total de muestra (mL)</td>
                  <td><input type="text" id="vol1Dureza" value="0"></td>
                  <td><input type="text" class="durSec2" id="vol2Dureza" value="0"></td>
                  <td><input type="text" class="durSec2" id="vol3Dureza" value="0"></td>
                  <td>V</td>

                </tr>
                <tr>
                  <td>F</td>
                  <td>Factor real Dureza</td>
                  <td><input type="text" id="real1Dureza" value="0"></td>
                  <td><input type="text" class="durSec2" id="real2Dureza" value="0"></td>
                  <td><input type="text" class="durSec2" id="real3Dureza" value="0"></td>
                  <td>F</td>
                </tr>
                <tr> 
                  <td>B</td>
                  <td>Factor de conversión mg/L</td>
                  <td><input type="text" id="conversion1Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="conversion2Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="conversion3Dureza" value="0"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>R</td>
                  <td>Resultado Ind.</td>
                  <td><input type="text" id="resInd1Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="resInd2Dureza" value="0"></td>
                  <td><input class="durSec2" type="text" id="resInd3Dureza" value="0"></td>
                  <td>V</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Fin modal Dureza --}}

{{-- Inicio modal Potable --}}
<div class="modal fade" id="modalPotable" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Potable</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionPotable" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionPotable')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoPotable" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor1</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>B</td>
                  <td>Factor de dilución</td>
                  <td><input type="text" id="dilucion1Potable"></td>
                  <td><input type="text" id="dilucion2Potable"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>X</td>
                  <td>Lectura 1</td>
                  <td><input type="text" id="lectura11Potable"></td>
                  <td><input type="text" id="lectura12Potable"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Y</td>
                  <td>Lectura 2</td>
                  <td><input type="text" id="lectura21Potable"></td>
                  <td><input type="text" id="lectura22Potable"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>Z</td>
                  <td>Lectura 3</td>
                  <td><input type="text" id="lectura31Potable"></td>
                  <td><input type="text" id="lectura32Potable"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Volumen de muestra</td>
                  <td><input type="text" id="volM1Potable"></td>
                  <td><input type="text" id="volM2Potable"></td>
                  <td>C</td>
                </tr>
                <tr>
                  <td>A</td>
                  <td>Promedio</td>
                  <td><input type="text" id="prom1Potable"></td>
                  <td><input type="text" id="prom2Potable"></td>
                  <td>C</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{-- Fin modal Potable --}}


{{-- Inicio modal Dureza Dif --}}
<div class="modal fade" id="modalDurezaDif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Dureza Diferencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDurezaDif" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDurezaDif')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoDurezaDif" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
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
                  <td>Dureza Magnesio</td>
                  <td>DT - DC</td>
                  <td><input type="number" id="durezaTDurezaDif" placeholder="Sin resultado"> - <input type="number"
                      id="durezaCDurezaDif" placeholder="Sin resultado"></td>
                  <td>Diferencia</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Fin modal dureza dif --}}
{{--todo Fin Modal Potable --}}


{{--todo Inicio modal MB --}}
{{-- Inicio modalo Coliformes alimentos --}}

<div class="modal fade" id="modalColiformesAlimentos" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Coliformes alimentos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionColAli" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionColAli')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" id="resultadoColAli" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-2">

          </div>

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
              <tbody>

                <tr>
                  <td>Pres.</td>
                  <td>24hrs</td>
                  <td><input type="text" id="pres124ColAli" value="0"></td>
                  <td><input type="text" id="pres224ColAli" value="0"></td>

                </tr>
                <tr>
                  <td>Pres.</td>
                  <td>48hrs</td>
                  <td><input type="text" id="pres148ColAli" value="0"></td>
                  <td><input type="text" id="pres248ColAli" value="0"></td>

                </tr>
                <tr>
                  <td>Confir.</td>
                  <td>24hrs</td>
                  <td><input type="text" id="confir124ColAli" value="0"></td>
                  <td><input type="text" id="confir224ColAli" value="0"></td>

                </tr>
                <tr>
                  <td>Confir.</td>
                  <td>48hrs</td>
                  <td><input type="text" id="confir148ColAli" value="0"></td>
                  <td><input type="text" id="confir248ColAli" value="0"></td>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Fin modalo Coliformes alimentos --}}

{{-- Inicio Modal E.Coli --}}

<div class="modal fade" id="modalEcoli" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <select type="text" class="form-control" id="observacionEcoli" placeholder="Seleciona uno">
                <option value="0">Seleccione uno</option>
                <option value="1">Colonias circulares con centro negro, planas con brillo metálico</option>
                <option value="2">Colonias irregulares con centro negro, planas con brillo metálico</option>
                <option value="3">Colonias irregulares con centro negro, planas sin brillo metálico</option>
                <option value="4">Colonias circulares con centro negro, planas sin brillo metálico</option>
                <option value="5">Colonias incoloras, mucoides, irregulares</option>
              </select>
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionEcoli')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoEcoli" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Reactivo</th>
                  <th>Colonia 1</th>
                  <th>Colonia 2</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td>Indol</td>
                  <td><input type="text" id="indol1Ecoli" value=""></td>
                  <td><input type="text" id="indol2Ecoli" value=""></td>
                </tr>
                <tr>
                  <td>RM</td>
                  <td><input type="text" id="rm1Ecoli" value=""></td>
                  <td><input type="text" id="rm2Ecoli" value=""></td>
                </tr>
                <tr>
                  <td>VP</td>
                  <td><input type="text" id="vp1Ecoli" value=""></td>
                  <td><input type="text" id="vp2Ecoli" value=""></td>
                </tr>
                <tr>
                  <td>Citrato</td>
                  <td><input type="text" id="citrato1Ecoli" value=""></td>
                  <td><input type="text" id="citrato2Ecoli" value=""></td>
                </tr>
                <tr>
                  <td>BGN</td>
                  <td><input type="text" id="bgn1Ecoli" value=""></td>
                  <td><input type="text" id="bgn2Ecoli" value=""></td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Fin Modal E.Coli --}}


{{-- Inicio modal Coliforme --}}

<div class="modal fade" id="modalCapturaCol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Coliformes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionCol" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionCol')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" id="resultadoCol" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-2">
            <button type="button" id="metodoCortoCol"> <i class="voyager-window-list"></i></button>
            <button type="button" id="btnCleanColiforme"> <i class="fas fa-eraser"></i></button>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>D1 | D2 | D3</td>
                  <td>Diluciones</td>
                  <td>
                    <input type="text" id="dil1Col" value="0" style="width: 60px;">
                    <input type="text" id="dil2Col" value="0" style="width: 60px;">
                    <input type="text" id="dil3Col" value="0" style="width: 60px;">
                  </td>
                  <td>

                  </td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>NMP</td>
                  <td>Indice NMP</td>
                  <td><input type="text" id="nmp1Col" value="0"></td>
                  <td></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>G3</td>
                  <td>mL De muestra en todos los tubos</td>
                  <td><input type="text" id="todos1Col" value="0"></td>
                  <td></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>G2</td>
                  <td>mL De muestra en tubos negativos</td>
                  <td><input type="text" id="negativos1Col"></td>
                  <td></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>G1</td>
                  <td># de tubos positivos</td>
                  <td><input type="text" id="positivos1Col"></td>
                  <td></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>P1 - P9</td>
                  <td>Prueba presuntiva 24hrs / 48hrs</td>
                  <td>
                    <input type="text" id="pre1Col" value="0" style="width: 60px;">
                    <input type="text" id="pre2Col" value="0" style="width: 60px;">
                    <input type="text" id="pre3Col" value="0" style="width: 60px;">
                    <br>
                    <input type="text" id="pre4Col" value="0" style="width: 60px;">
                    <input type="text" id="pre5Col" value="0" style="width: 60px;">
                    <input type="text" id="pre6Col" value="0" style="width: 60px;">
                    <br>
                    <input type="text" id="pre7Col" value="0" style="width: 60px;">
                    <input type="text" id="pre8Col" value="0" style="width: 60px;">
                    <input type="text" id="pre9Col" value="0" style="width: 60px;">
                  </td>
                  <td>
                    <input type="text" id="pre10Col" value="0" style="width: 60px">
                    <input type="text" id="pre11Col" value="0" style="width: 60px">
                    <input type="text" id="pre12Col" value="0" style="width: 60px">
                    <br>
                    <input type="text" id="pre13Col" value="0" style="width: 60px">
                    <input type="text" id="pre14Col" value="0" style="width: 60px">
                    <input type="text" id="pre15Col" value="0" style="width: 60px">
                    <br>
                    <input type="text" id="pre16Col" value="0" style="width: 60px">
                    <input type="text" id="pre17Col" value="0" style="width: 60px">
                    <input type="text" id="pre18Col" value="0" style="width: 60px">
                  </td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>C1 - C9</td>
                  <td><div id="tituloColiformes"></div> </td>
                  <td>
                    <input type="text" id="con1Col" value="0" style="width: 60px;">
                    <input type="text" id="con2Col" value="0" style="width: 60px;">
                    <input type="text" id="con3Col" value="0" style="width: 60px;">
                    <br>
                    <input type="text" id="con4Col" value="0" style="width: 60px;">
                    <input type="text" id="con5Col" value="0" style="width: 60px;">
                    <input type="text" id="con6Col" value="0" style="width: 60px;">
                    <br>
                    <input type="text" id="con7Col" value="0" style="width: 60px;">
                    <input type="text" id="con8Col" value="0" style="width: 60px;">
                    <input type="text" id="con9Col" value="0" style="width: 60px;">
                  </td>
                  <td>
                    <input type="text" id="con10Col" value="0" style="width: 60px">
                    <input type="text" id="con11Col" value="0" style="width: 60px">
                    <input type="text" id="con12Col" value="0" style="width: 60px">
                    <br> 
                    <input type="text" id="con13Col" value="0" style="width: 60px">
                    <input type="text" id="con14Col" value="0" style="width: 60px">
                    <input type="text" id="con15Col" value="0" style="width: 60px">
                    <br>
                    <input type="text" id="con16Col" value="0" style="width: 60px">
                    <input type="text" id="con17Col" value="0" style="width: 60px">
                    <input type="text" id="con18Col" value="0" style="width: 60px">
                  </td>
                  <td>V</td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Fin modal Coliformes --}}

{{-- Inico Modal Enterococos --}}

<div class="modal fade" id="modalCapturaEnt" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionEnt" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionEnt')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" id="resultadoEnt" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-2">
            <button type="button" id="metodoCortoEnt"> <i class="voyager-window-list"></i></button>
            <button type="button" id="btnCleanColiforme"> <i class="fas fa-eraser"></i></button>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th></th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>D1 | D2 | D3</td>
                  <td>Diluciones</td>
                  <td>
                    <input type="text" id="endil1Ent" value="0" style="width: 60px;">
                    <input type="text" id="endil2Ent" value="0" style="width: 60px;">
                    <input type="text" id="endil3Ent" value="0" style="width: 60px;">
                  </td>
                  </td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>NMP</td>
                  <td>Indice NMP</td>
                  <td><input type="text" id="ennmp1Ent" value="0"></td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>G3</td>
                  <td>mL De muestra en todos los tubos</td>
                  <td><input type="text" id="entodos1Ent" value="0"></td>
                  {{-- <td><input type="text" id="todos2" value="0"></td> --}}
                  <td>V</td>
                </tr>
                <tr>
                  <td>G2</td>
                  <td>mL De muestra en tubos negativos</td>
                  <td><input type="text" id="ennegativos1Ent" value="0"></td>
                  {{-- <td><input type="text" id="negativos2" value="0"></td> --}}
                  <td>V</td>
                </tr>
                <tr>
                  <td>G1</td>
                  <td># de tubos positivos</td>
                  <td><input type="text" id="enpositivos1Ent" value="0"></td>
                  {{-- <td><input type="text" id="positivos2" value="0"></td> --}}
                  <td>V</td>
                </tr>
                <tr>
                  <td>P1 - P9</td>
                  <td>Prueba presuntiva 24 hrs / 48 hrs</td>
                  <td>
                    <center>24 Hrs</center> <br>
                    <input type="text" id="enPre1Ent" style="width: 60px;">
                    <input type="text" id="enPre2Ent" style="width: 60px;">
                    <input type="text" id="enPre3Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enPre4Ent" style="width: 60px;">
                    <input type="text" id="enPre5Ent" style="width: 60px;">
                    <input type="text" id="enPre6Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enPre7Ent" style="width: 60px;">
                    <input type="text" id="enPre8Ent" style="width: 60px;">
                    <input type="text" id="enPre9Ent" style="width: 60px;">
                  </td>
                  <td>
                    <center>48 Hrs</center> <br>
                    <input type="text" id="enPre12Ent" style="width: 60px;">
                    <input type="text" id="enPre22Ent" style="width: 60px;">
                    <input type="text" id="enPre32Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enPre42Ent" style="width: 60px;">
                    <input type="text" id="enPre52Ent" style="width: 60px;">
                    <input type="text" id="enPre62Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enPre72Ent" style="width: 60px;">
                    <input type="text" id="enPre82Ent" style="width: 60px;">
                    <input type="text" id="enPre92Ent" style="width: 60px;">
                  </td>
                  <td>V</td>
                </tr>
                <tr>
                  <td>C1 - C9</td>
                  <td>Prueba confirmativa (1° - 24 hrs )/(2° - 48 hrs)</td>
                  <td>
                    <center>24 Hrs</center><br>
                    <input type="text" id="enCon1Ent" style="width: 60px;">
                    <input type="text" id="enCon2Ent" style="width: 60px;">
                    <input type="text" id="enCon3Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enCon4Ent" style="width: 60px;">
                    <input type="text" id="enCon5Ent" style="width: 60px;">
                    <input type="text" id="enCon6Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enCon7Ent" style="width: 60px;">
                    <input type="text" id="enCon8Ent" style="width: 60px;">
                    <input type="text" id="enCon9Ent" style="width: 60px;">
                  </td>
                  <td>
                    <center>48 Hrs</center><br>
                    <input type="text" id="enCon12Ent" style="width: 60px;">
                    <input type="text" id="enCon22Ent" style="width: 60px;">
                    <input type="text" id="enCon32Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enCon42Ent" style="width: 60px;">
                    <input type="text" id="enCon52Ent" style="width: 60px;">
                    <input type="text" id="enCon62Ent" style="width: 60px;">
                    <br>
                    <input type="text" id="enCon72Ent" style="width: 60px;">
                    <input type="text" id="enCon82Ent" style="width: 60px;">
                    <input type="text" id="enCon92Ent" style="width: 60px;">
                  </td>
                  <td>V</td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{-- Fin Modal Enterococos --}}

{{-- Inicio Modal HH --}}

<div class="modal fade" id="modalCapturaHH" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados HH</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionHH" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionHH')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoHH" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>

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
              <tbody>
                <tr>
                  <td>A</td>
                  <td>A. lumbicoides</td>
                  <td><input type="text" id="lum1HH" value="0"></td>
                  <td><input type="text" id="lum2HH" value="0"></td>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Unicinarias</td>
                  <td><input type="text" id="uni1HH" value="0"></td>
                  <td><input type="text" id="uni2HH" value="0"></td>
                </tr>
                <tr>
                  <td>C</td>
                  <td>H. NANA</td>
                  <td><input type="text" id="na1HH" value="0"></td>
                  <td><input type="text" id="na2HH" value="0"></td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>T. Trichiura</td>
                  <td><input type="text" id="tri1HH" value="0"></td>
                  <td><input type="text" id="tri2HH" value="0"></td>
                </tr>
                <tr>
                  <td>E</td>
                  <td>TAENIA SP</td>
                  <td><input type="text" id="sp1HH" value="0"></td>
                  <td><input type="text" id="sp2HH" value="0"></td>
                </tr>

                <tr>
                  <td>G</td>
                  <td>Vol. Muestra</td>
                  <td><input type="text" id="volH1HH" value="0"></td>
                  <td><input type="text" id="volHHH" value="0"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


{{-- Fin Modal HH --}}

{{-- Inicio Modal Coliformes Alimentos --}}


{{-- Fin Modal Coliforme Alimentos --}}

{{-- Incio Moda DBo --}}

<div class="modal fade" id="modalCapturaDbo" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDbo" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDbo')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" id="resultadoDbo" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="sugeridoDbo">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A</td>
                  <td>Oxigeno disuelto inicial</td>
                  <td><input type="text" id="oxiInicial1Dbo" value="0"></td>
                  <td><input type="text" id="oxiInicial2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Oxigeno disuelto final</td>
                  <td><input type="text" id="oxiFinal1Dbo" value="0"></td>
                  <td><input type="text" id="oxiFinal2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Vol botella winkler</td>
                  <td><input type="text" id="win1Dbo" value="300"></td>
                  <td><input type="text" id="win2Dbo" value="300"></td>
                  <th>C</th>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Volumen de muestra</td>
                  <td><input type="text" id="volDbo1Dbo" value="0"></td>
                  <td><input type="text" id="volDbo2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>E</td>
                  <td>% dilucion (DBO5)</td>
                  <td><input type="text" id="dil1Dbo" value="0"></td>
                  <td><input type="text" id="dil2Dbo" value="0"></td>
                  <th>C</th>
                </tr>
                <tr>
                  <td>G</td>
                  <td>No De botella Od</td>
                  <td><input type="text" id="od1Dbo" value="0"></td>
                  <td><input type="text" id="od2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>H</td>
                  <td>No De botella final</td>
                  <td><input type="text" id="botellaF1Dbo" value="0"></td>
                  <td><input type="text" id="botellaF2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>I</td>
                  <td>ph Inicial</td>
                  <td><input type="text" id="phIni1Dbo" value="0"></td>
                  <td><input type="text" id="phIni2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>J</td>
                  <td>pH Final</td>
                  <td><input type="text" id="phF1Dbo" value="0"></td>
                  <td><input type="text" id="phF2Dbo" value="0"></td>
                  <th>V</th>
                </tr>


              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modalCapturaDboIno" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados DBO Con Inoculo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDboIno" placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDboIno')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" id="resultadoDboIno" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="sugeridoDboIno">
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                  <th>Valor2</th>
                  <th>Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A</td>
                  <td>Oxigeno disuelto inicial</td>
                  <td><input type="text" id="oxiInicialIno1Dbo" value="0"></td>
                  <td><input type="text" id="oxiInicialIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>B</td>
                  <td>Oxigeno disuelto final</td>
                  <td><input type="text" id="oxiFinalIno1Dbo" value="0"></td>
                  <td><input type="text" id="oxiFinalIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>C</td>
                  <td>Vol de inoculo de la muestra</td>
                  <td><input type="text" id="volInoMuestra1Dbo"></td>
                  <td><input type="text" id="volInoMuestra2Dbo"></td>
                  <th>C</th>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Oxigeno disuelto del inoculo Ini</td>
                  <td><input type="text" id="oxigenoDisueltoIniIno1Dbo" value="0"></td>
                  <td><input type="text" id="oxigenoDisueltoIniIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>E</td>
                  <td>Oxigeno disuelto del inoculo Fin</td>
                  <td><input type="text" id="oxigenoDisueltoFinIno1Dbo" value="0"></td>
                  <td><input type="text" id="oxigenoDisueltoFinIno2Dbo" value="0"></td>
                  <th>C</th>
                </tr>
                <tr>
                  <td>G</td>
                  <td>Vol Total del frasco</td>
                  <td><input type="text" id="volTotalFrascoIno1Dbo" value="0"></td>
                  <td><input type="text" id="volTotalFrascoIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>H</td>
                  <td>Vol del inoculo en el Blanco</td>
                  <td><input type="text" id="volIno1Dbo" value="0"></td>
                  <td><input type="text" id="volIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>I</td>
                  <td>Vol muestra siembra</td>
                  <td><input type="text" id="volMuestraSiemIno1Dbo" value="0"></td>
                  <td><input type="text" id="volMuestraSiemIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>J</td>
                  <td>Porcentaje de dilucion</td>
                  <td><input type="text" id="porcentajeIno1Dbo" value="0"></td>
                  <td><input type="text" id="porcentajeIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>P</td>
                  <td>Fact. D. de P-Dilución</td>
                  <td><input type="text" id="preIno1Dbo"></td>
                  <td><input type="text" id="preIno2Dbo"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>K</td>
                  <td>Vol botella Winker</td> 
                  <td><input type="text" id="volWinkerIno1Dbo" value="0"></td>
                  <td><input type="text" id="volWinkerIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>L</td>
                  <td>No de botella OD</td>
                  <td><input type="text" id="noBotellaIno1Dbo" value="0"></td>
                  <td><input type="text" id="noBotellaIno2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>M</td>
                  <td>No de botella Final</td>
                  <td><input type="text" id="noBotellaFin1Dbo" value="0"></td>
                  <td><input type="text" id="noBotellaFin2Dbo" value="0"></td>
                  <th>V</th>
                </tr>
                <tr>
                  <td>N</td>
                  <td>pH Inicial</td>
                  <td><input type="text" id="phInicialIno1Dbo" value="0"></td>
                  <td><input type="text" id="phInicialIno2Dbo" value="0"></td>
                  <th>V</th> 
                </tr>
                <tr>
                  <td>O</td>
                  <td>pH Final</td>
                  <td><input type="text" id="phFinIno1Dbo" value="0"></td>
                  <td><input type="text" id="phFinIno2Dbo" value="0"></td>
                  <th>V</th>
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
    <div class="modal fade" id="modalCapturaDboBlanco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Captura Dbo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="">Observación</label>
                            <input type="text" class="form-control" id="observacionDboBlanco" placeholder="Observacion de la muestra">
                          </div>
                          <div class="form-group">
                            <button class="btn btn-success" type="button" onclick="setObservacion('observacionDboBlanco')"><i
                                class="voyager-check"></i> Aplicar</button>
                          </div>
                        </div>

                        <div class="col-md-2">
                          <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <input type="text" id="resultadoDboBlanco" style="font-size: 20px;color:red;" placeholder="Resultado">
                          </div>
                        </div>
                                <div class="col-md-12">
                                    <table class="table" id="">
                                        <thead>
                                            <tr>
                                                <th>Parametro</th>
                                                <th>Descripción</th>
                                                <th>Valor</th>
                                                <th>Valor2</th>
                                                <th>Tipo</th>
                                            </tr>
                                        </thead>
                                        <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                        <tbody>
                                            <tr>
                                                <td>OI</td>
                                                <td>Oxigeno inicial</td>
                                                <td><input type="text" id="oxigenoIncialB1" value="0"></td>
                                                <td><input type="text" id="oxigenoIncialB2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>OF</td>
                                                <td>Oxigeno final</td>
                                                <td><input type="text" id="oxigenofinalB1" value="0"></td>
                                                <td><input type="text" id="oxigenofinalB2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>V</td>
                                                <td>Volumen de muestra</td>
                                                <td><input type="text" id="volMuestraB1" value="0"></td>
                                                <td><input type="text" id="volMuestraB2" value="0"></td>
                                                <th>V</th>
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

{{-- Fin Moda DBo --}}

<!-- Inicio Color -->
<div class="modal fade" id="modalCapturaColorVerdadero" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Color</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="observacionColorDir1">Observacion 436</label>
                  <input type="text" class="form-control" id="observacionColorDir1" placeholder="Observacion de la muestra 1">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="observacionColorDir2">Observacion 525</label>
                  <input type="text" class="form-control" id="observacionColorDir2" placeholder="Observacion de la muestra 2">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="observacionColorDir3">Observacion 620</label>
                  <input type="text" class="form-control" id="observacionColorDir3" placeholder="Observacion de la muestra 3">
                </div>
              </div>
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionColorDir1','observacionColorDir2','observacionColorDir3')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" id="resultadoColor1" style="font-size: 20px;color:red;" placeholder="Resultado 436"> &nbsp;
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" id="resultadoColor2" style="font-size: 20px;color:red;" placeholder="Resultado 525"> &nbsp;
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input type="text" id="resultadoColor3" style="font-size: 20px;color:red;" placeholder="Resultado 620"> &nbsp;
            </div>
          </div>

          <div class="col-md-12">
            <table class="table" id="">
              <thead>
                <tr>
                  <th>Parametro</th>
                  <th>Descripción</th>
                  <th>Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>V</td>
                  <td>Vol muestra</td>
                  <td><input type="text" id="volColor" ></td>
                </tr>
                <tr>
                  <td>Espectral 436 nm</td>
                  <td colspan="3">
                    <div class="row">
                      <div class="col-md-12">
                      </div>
                      <div class="col-md-2">
                        <label for="fdColor1">Fd</label>
                        <br>
                        <input type="text" id="fdColor1" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="longitud1">Longitud</label>
                        <input type="text" id="longitud1" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="abs11Color">Abs1</label>
                        <input type="text" id="abs11Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs2</label>
                        <input type="text" id="abs12Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs3</label>
                        <input type="text" id="abs13Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs Prom</label>
                        <input type="text" id="absProm1" style="width: 120px;">
                      </div>
                      
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Espectral 525 nm</td>
                  <td colspan="3">
                    <div class="row">
                      <div class="col-md-12">
                      </div>
                      <div class="col-md-2">
                        <label for="fdColor2">Fd</label>
                        <br>
                        <input type="text" id="fdColor2" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="longitud2">Longitud</label>
                        <input type="text" id="longitud2" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="abs21Color">Abs1</label>
                        <input type="text" id="abs21Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs2</label>
                        <input type="text" id="abs22Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs3</label>
                        <input type="text" id="abs23Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs Prom</label>
                        <input type="text" id="absProm2" style="width: 120px;">
                      </div>
                      
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Espectral 620 nm</td>
                  <td colspan="3">
                    <div class="row">
                      <div class="col-md-12">
                      </div>
                      <div class="col-md-2">
                        <label for="fdColor3">Fd</label>
                        <br>
                        <input type="text" id="fdColor3" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="longitud3">Longitud</label>
                        <input type="text" id="longitud3" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="abs33Color">Abs1</label>
                        <input type="text" id="abs31Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs2</label>
                        <input type="text" id="abs32Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs3</label>
                        <input type="text" id="abs33Color" style="width: 120px;">
                      </div>
                      <div class="col-md-2">
                        <label for="">Abs Prom</label>
                        <input type="text" id="absProm3" style="width: 120px;">
                      </div>
                      
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>D</td>
                  <td>Ph color</td>
                  <td><input type="text" id="phpColor" value="0"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

 <!-- Fin Color -->

 {{--* Inicio Modal Color --}}
<div class="modal fade" id="modalDirectoColorPh" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Captura de resultados Directo Color</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Observación</label>
              <input type="text" class="form-control" id="observacionDirectoColorPh"
                placeholder="Observacion de la muestra">
            </div>
            <div class="form-group">
              <button class="btn btn-success" type="button" onclick="setObservacion('observacionDirectoColorPh')"><i
                  class="voyager-check"></i> Aplicar</button>
            </div>
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <input type="text" id="resultadoColorDir" style="font-size: 20px;color:red;" placeholder="Resultado">
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
                  <td>A.</td>
                  <td>Color</td>
                  <td><input type="text" id="colorResDir"></td>
                  <td><input type="text" id=""></td>
                </tr>
                <tr>
                  <td>D.</td>
                  <td>Volumen muestra.</td>
                  <td><input type="text" id="volColDir"></td>
                  <td><input type="text" id=""></td>

                </tr>
                <tr>
                  <td>E.</td>
                  <td>ph.</td>
                  <td><input type="text" id="phColorDir"></td>
                  <td><input type="text" id=""></td>

                </tr>
        
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
{{--* Fin Modal Color --}}


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


@endsection

@section('javascript')
<script src="{{asset('/public/js/laboratorio/analisis/captura.jsx')}}?v=1.2.8"></script>

<script src="{{asset('/assets/summer/summernote.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@stop