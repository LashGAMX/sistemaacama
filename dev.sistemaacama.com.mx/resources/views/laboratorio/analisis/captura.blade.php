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
            <h4 class="text-warning">Datos lote</h4>
          </center>
          <div class="row">
            <div class="col-md-6">
              <label for="">Filtros de busqueda</label>
              <div class="form-group">
                <label for="">Parametro</label>
                <select class="form-control select2" id="parametro">
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
            Fecha recepción: <input type="date" id="fechaAsignar"> <button class="btn-success"><i
                class="fas fa-search"></i> Buscar</button>
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
<div class="modal fade" id="modalDetalleLote" tabindex="-1" aria-labelledby="modalDetalleLoteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
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
                <hr />
                <table class="table">
                  <thead>
                    <th>Temperatura</th>
                    <th>Hora entrada</th>
                    <th>Hora salida</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="number" id="3temperaturaGA" /></td>
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
<div class="modal fade" id="modalCapturaSolidosDir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <input type="text" class="form-control" id="observacionSolidosDir" placeholder="Observacion de la muestra">
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
<div class="modal fade" id="modalCapturaSolidosDif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <input type="text" class="form-control" id="observacionSolidosDif" placeholder="Observacion de la muestra">
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
          <div class="col-md-5">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoSolidosDif" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-5">
            <label for="preResDif">Pres resultado</label>
            <input type="text" id="preResDifSolidosDif" style="font-size: 20px;color:blue;" placeholder="No. Serie Crisol">
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
        <h5 class="modal-title" id="">Captura de resultados Solidos</h5>
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

          <div class="col-md-2">
            <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i>
              Ejecutar</button>
          </div>
          <div class="col-md-5">
            <div class="form-group">
              <label for="resultado">Resultado</label>
              <input type="text" id="resultadoSolidos" style="font-size: 20px;color:red;" placeholder="Resultado">
            </div>
          </div>
          <div class="col-md-5">
            <input type="text" id="crisolSolidos" style="font-size: 20px;color:blue;" placeholder="No. Serie Matraz">
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
                  <td>Peso constante c/muestra 1 A</td>
                  <td><input name="campos" type="text" id="pcm11Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pcm12Solidos" value="0"></td>
                </tr>
                <tr>
                  <td>Peso constante c/muestra 2 A</td>
                  <td><input name="campos" type="text" id="pcm21Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pcm22Solidos" value="0"></td>
                </tr>
                <tr>
                  <td>Peso constante 1 B</td>
                  <td><input name="campos" type="text" id="pc1Solidos" value="0"></td>
                  <td><input name="campos" type="text" id="pc2Solidos" value="0"></td>
                </tr>
                <tr>
                  <td>Peso constante 2 B</td>
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

{{--todo Inicio Modal Volumetria  --}}
  {{--* Inicio Modal Nitrogeno --}}
  <div class="modal fade" id="modalCapturaNitrogenoVol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <input type="text" class="form-control" id="observacionNitrogenoVol" placeholder="Observacion de la muestra">
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
                <input type="text" id="resultadoCloroVol" style="font-size: 20px;color:red;"
                    placeholder="Resultado">
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
  <div class="modal fade" id="modalDboVol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <input type="text" class="form-control" id="observacionDboVol" placeholder="Observacion de la muestra">
              </div>
              <div class="form-group">
                <button class="btn btn-success" type="button" onclick="setObservacion('observacionDboVol')"><i
                    class="voyager-check"></i> Aplicar</button>
              </div>
            </div>
  
            <div class="col-md-2">
              <button class="btn btn-primary btnEjecutar"><i class="voyager-play"></i> Ejecutar</button>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <input type="text" id="resultadoDboVol" style="font-size: 20px;color:red;"
                    placeholder="Resultado">
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
                        <td><input type="text" id="abs1DboVol"></td>
                        <td><input type="text" id="abs2DboVol"></td>
                        <td>C</td>
                    </tr>
                    <tr>
                        <td>CA</td>
                        <td>Blanco</td>
                        <td><input name="campos" type="number" id="blanco1DboVol"></td>
                        <td><input name="campos" type="number" id="blanco2DboVol"></td>
                        <td>F</td>
                    </tr>    
                    <tr>
                        <td>CB</td>
                        <td>b</td>
                        <td><input name="campos" type="number" id="b1DboVol" disabled></td>
                        <td><input name="campos" type="number" id="b2DboVol"></td>
                        <td>F</td>
                    </tr>
                    <tr>
                        <td>CM</td>
                        <td>m</td>
                        <td><input name="campos" type="number" id="m1DboVol" disabled></td>
                        <td><input name="campos" type="number" id="m2DboVol"></td>
                        <td>F</td>
                    </tr>
                    <tr>
                        <td>CR</td>
                        <td>r</td>
                        <td><input name="campos" type="number" id="r1DboVol" disabled></td>
                        <td><input name="campos" type="number" id="r2DboVol"></td>
                        <td>F</td>
                    </tr>          
                    <tr>
                        <td>D</td>
                        <td>Factor dilucion</td>
                        <td><input type="number" id="fDilucion1DboVol" disabled></td>
                        <td><input type="number" id="fDilucion2DboVol" disabled></td>
                        <td>V</td>
                    </tr>

                    <tr>
                        <td>E</td>
                        <td>Vol de la muestra</td>
                        <td><input name="campos" type="number" id="volMuestra1DboVol"></td>
                        <td><input name="campos" type="number" id="volMuestra2DboVol"></td>
                        <td>V</td>
                    </tr>
                    <tr>
                        <td>X</td>
                        <td>Absorbancia1</td>
                        <td><input name="campos" type="number" id="abs11DboVol"></td>
                        <td><input name="campos" type="number" id="abs12DboVol"></td>
                        <td>V</td>
                    </tr>
                    <tr>
                        <td>Y</td>
                        <td>Absorbancia2</td>
                        <td><input name="campos" type="number" id="abs21DboVol"></td>
                        <td><input name="campos" type="number" id="abs22DboVol"></td>
                        <td>V</td>
                    </tr>
                    <tr>
                        <td>Z</td>
                        <td>Absorbancia3</td>
                        <td><input name="campos" type="number" id="abs31DboVol"></td>
                        <td><input name="campos" type="number" id="abs32DboVol"></td>
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
{{--todo Fin Modal Volumetria  --}}

{{--! FIN Modal de capturas de parametros --}}

@endsection

@section('javascript')
<script src="{{asset('/public/js/laboratorio/analisis/captura.js')}}?v=0.0.2"></script>
<script src="{{ asset('/public/js/libs/componentes.js')}}"></script>
<script src="{{ asset('/public/js/libs/tablas.js') }}"></script>
<script src="{{asset('/assets/summer/summernote.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@stop