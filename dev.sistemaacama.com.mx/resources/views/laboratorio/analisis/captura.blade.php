@extends('voyager::master')
@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <center><h4 class="text-info">Lotes</h4></center>
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
            <center><h4 class="text-warning">Datos lote</h4></center>
            <div class="row">
              <div class="col-md-6">
                <label for="">Filtros de busqueda</label>
                <div class="form-group">
                  <label for="">Parametro</label>
                    <select class="form-control select2" id="parametro"> 
                      <option value="0">Sin seleccionar</option>
                      @foreach ($model as $item)
                        <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
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
                  <button class="btn-info" id="btnBuscarLote" style="width: 100%"><i class="fas fa-search"></i> Buscar lote</button>
                </div>
                <div class="form-group">
                  <button class="btn-success" id="btnCrearLote" style="width: 100%"><i class="fas fa-plus"></i> Crear lote</button>
                </div>
                <div class="form-group">
                  <button class="btn-primary" style="width: 100%" id="btnPendientes" data-toggle="modal" data-target="#modalPendientes"><i class="voyager-news"></i> Pendientes</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <center><h4 class="text-success">Captura de resultados</h4></center>
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
            <button class="btn-success" data-toggle="modal" data-target="#modalCalidad"><i class="fas fa-vial"></i> Control</button>
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
        <h5 class="modal-title" id="exampleModalLabel">Asignar lote: <input type="text" style="border:none" id="loteAsignar"></h5>
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
                <td><input type="text" id="fechaAnalisisAsignar" style="border: none" ></td>
              </tr>
            </table>
          </div>
          <div class="col-md-12">
            <center>
              <table>
                <tr>
                  <td><center>Asignados</center></td>
                  <td><center>Liberados</center></td>
                  <td><center>Por asignar</center></td>
                </tr>
                <tr>
                  <td><center><input type="number" style="border:none;text-align: center" disabled id="asignadoLote"></center></td>
                  <td><center><input type="number" style="border:none;text-align: center" disabled id="liberadoLote"></center></td>
                  <td><center><input type="number" style="border:none;text-align: center" disabled id="porAsingarLote"></center></td>
                </tr>
              </table>
            </center>
            <br>
            Fecha recepción: <input type="date" id="fechaAsignar"> <button class="btn-success"><i class="fas fa-search"></i> Buscar</button>
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
        <h5 class="modal-title" id="modalDetalleLoteLabel">Detalle lote: <input type="" id="tituloLote" style="border:none;width: 80%;"></h5>
      </div>
      <div class="modal-body">
       {{-- Inicio de Body  --}}
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">General</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Datos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Plantilla</button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
            Dato 1
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <!-- inicio tabla grasas   -->
          <div class="row">
            <div class="col-md-4">
              <button type="button" id="btnGuardarDetalleGasas" onclick="guardarDetalleGrasas()" class="btn btn-primary">Guardar</button>
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
                      <td><input type="text" id="temp1" /></td>
                      <td><input type="datetime-local" id="entrada1" /></td>
                      <td><input type="datetime-local" id="salida1" /></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><input type="text" id="temp2" /></td>
                      <td><input type="datetime-local" id="entrada2" /></td>
                      <td><input type="datetime-local" id="salida2" /></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><input type="text" id="temp3" /></td>
                      <td><input type="datetime-local" id="entrada3" /></td>
                      <td><input type="datetime-local" id="salida3" /></td>
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
                      <td><input type="datetime-local" id="2entrada1" /></td>
                      <td><input type="datetime-local" id="2salida1" /></td>
                      <td><input type="datetime-local" id="2pesado1" /></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><input type="datetime-local" id="2entrada2" /></td>
                      <td><input type="datetime-local" id="2salida2" /></td>
                      <td><input type="datetime-local" id="2pesado2" /></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><input type="datetime-local" id="2entrada3" /></td>
                      <td><input type="datetime-local" id="2salida3" /></td>
                      <td><input type="datetime-local" id="2pesado3" /></td>
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
                      <td><input type="number" id="3temperatura" /></td>
                      <td><input type="datetime-local" id="3entrada" /></td>
                      <td><input type="datetime-local" id="3salida" /></td>
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
                      <td><input type="datetime-local" id="4entrada" /></td>
                      <td><input type="datetime-local" id="4salida" /></td>
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
                      <td><input type="datetime-local" id="5entrada" /></td>
                      <td><input type="datetime-local" id="5salida" /></td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>

          </div>
          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
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
                  <button type="button"  id="btnSetControl" class="btn btn-primary">Generar</button>
              </div>
        
      </div>
  </div>
</div>

{{--? Fin modal control calidad --}}

{{--todo INICIO Modal de capturas de parametros --}}

  {{--? Inicio COT   --}}
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
                              <input type="text" class="form-control" id="observacionCOT"
                                  placeholder="Observacion de la muestra">
                          </div>
                          <div class="form-group">
                              <button class="btn btn-success" type="button" onclick="setObservacion('observacionCOT')"
                                  id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
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
                              <input type="text" id="resultadoCOT" style="font-size: 20px;color:red;"
                                  placeholder="Resultado">
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
                                      <td><input name="campos" type="number" id="b1COT" ></td>
                                      <td><input name="campos" type="number" id="b2COT" ></td>
                                      <td>F</td>
                                  </tr>
                                  <tr>
                                      <td>CM</td>
                                      <td>m</td>
                                      <td><input name="campos" type="number" id="m1COT" ></td>
                                      <td><input name="campos" type="number" id="m2COT" ></td>
                                      <td>F</td>
                                  </tr>
                                  <tr>
                                      <td>CR</td>
                                      <td>r</td>
                                      <td><input name="campos" type="number" id="r1COT" ></td>
                                      <td><input name="campos" type="number" id="r2COT" ></td>
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
  {{--? Fin COT   --}}

{{--todo FIN Modal de capturas de parametros --}}

@endsection  

@section('javascript')
    <script src="{{asset('/public/js/laboratorio/analisis/captura.js')}}?v=0.0.2"></script>
    <script src="{{ asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{ asset('/public/js/libs/tablas.js') }}"></script>
    <script src="{{asset('/assets/summer/summernote.js')}}"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@stop