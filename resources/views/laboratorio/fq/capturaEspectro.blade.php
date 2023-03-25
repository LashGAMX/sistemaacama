@extends('voyager::master')

@section('content')

@section('page_header')
<h6 class="page-title">
    <i class="voyager-window-list"></i>
    Captura de resultados
</h6>

<div class="container-fluid"> 
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $item)
                    <option value={{$item ->Id_parametro}}>({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control" id="fechaAnalisis">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" onclick="getDataCaptura()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
            <input id="idLote" hidden>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div id="divLote">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha lote</th>
                                    <th>Total asignados</th>
                                    <th>Total liberados</th>
                                    <th>Opc</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                            <div id="infoGlobal">
                                <button class="btn btn-secondary" id="btnGenControl"
                                    onclick="imprimir();">Imprimir</button>
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

            <div class="colo-md-12">
                <div class="row">
                    {{-- <div class="col-md-1">
                        <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                    </div> --}}
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="btnLiberarTodo">Liberar todo</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                            id="">Generar control</button>
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
                        <button class="btn btn-secondary" id="btnGenControles">Generar controles</button>
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
                                {{-- <th># Toma</th> --}}
                                <th>Norma</th>
                                <th>Resultado</th>
                                <th>Observacion</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCaptura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Captura de resultados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Observación</label>
                                    <input type="text" class="form-control" id="observacion"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" onclick="updateObsMuestraEspectro()"
                                        id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                </div>
                                <div class="col-md-2">
                                <label id="ph">pH</label>
                                <input type="text" disabled class="form-control" id="phCampo">
                                    </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="btnGuardar"><i class="voyager-upload"></i>
                                    Guardar</button>&nbsp;&nbsp;
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="btnEjecutar"><i class="voyager-play"></i>
                                    Ejecutar</button>
                            </div>
                            {{-- <div class="col-md-2">
                                <button class="btn btn-warning">Liberar</button>
                            </div> --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="resultado">Resultado</label>
                                    <input type="text" id="resultado" style="font-size: 20px;color:red;"
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
                                            <td><input type="text" id="abs1"></td>
                                            <td><input type="text" id="abs2"></td>
                                            <td>C</td>
                                        </tr>
                                        <tr>
                                            <td>CA</td>
                                            <td>Blanco</td>
                                            <td><input name="campos" type="number" id="blanco1"></td>
                                            <td><input name="campos" type="number" id="blanco2"></td>
                                            <td>F</td>
                                        </tr>
                                        <tr>
                                            <td>CB</td>
                                            <td>b</td>
                                            <td><input name="campos" type="number" id="b1" disabled></td>
                                            <td><input name="campos" type="number" id="b2" disabled></td>
                                            <td>F</td>
                                        </tr>
                                        <tr>
                                            <td>CM</td>
                                            <td>m</td>
                                            <td><input name="campos" type="number" id="m1" disabled></td>
                                            <td><input name="campos" type="number" id="m2" disabled></td>
                                            <td>F</td>
                                        </tr>
                                        <tr>
                                            <td>CR</td>
                                            <td>r</td>
                                            <td><input name="campos" type="number" id="r1" disabled></td>
                                            <td><input name="campos" type="number" id="r2" disabled></td>
                                            <td>F</td>
                                        </tr>
                                        <tr>
                                            <td>D</td>
                                            <td>Factor dilucion</td>
                                            <td><input type="number" id="fDilucion1" disabled></td>
                                            <td><input type="number" id="fDilucion2" disabled></td>
                                            <td>V</td>
                                        </tr>
                                        <tr id="conPh">
                                            <td>P</td>
                                            <td>pH Final</td>
                                            <td><input name="campos" type="number" id="phFin1"></td>
                                            <td><input name="campos" type="number" id="phFin2"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr id="conPh2">
                                            <td>P2</td>
                                            <td>pH Inicial</td>
                                            <td><input name="campos" type="number" id="phIni1" value="0"></td>
                                            <td><input name="campos" type="number" id="phIni2" value="0"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr id="conN1">
                                            <td>N1</td>
                                            <td>Nitratos</td>
                                            <td><input name="campos" type="number" id="nitratos1" value="0"></td>
                                            <td><input name="campos" type="number" id="nitratos2" value="0"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr id="conN2">
                                            <td>N2</td>
                                            <td>Nitritos</td>
                                            <td><input name="campos" type="number" id="nitritos1" value="0"></td>
                                            <td><input name="campos" type="number" id="nitritos2" value="0"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr id="conN3">
                                            <td>N3</td>
                                            <td>Sulfuros</td>
                                            <td><input name="campos" type="number" id="sulfuros1" value="0"></td>
                                            <td><input name="campos" type="number" id="sulfuros2" value="0"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>E</td>
                                            <td>Vol de la muestra</td>
                                            <td><input name="campos" type="number" id="volMuestra1"></td>
                                            <td><input name="campos" type="number" id="volMuestra2"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>X</td>
                                            <td>Absorbancia1</td>
                                            <td><input name="campos" type="number" id="abs11"></td>
                                            <td><input name="campos" type="number" id="abs12"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>Y</td>
                                            <td>Absorbancia2</td>
                                            <td><input name="campos" type="number" id="abs21"></td>
                                            <td><input name="campos" type="number" id="abs22"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>Z</td>
                                            <td>Absorbancia3</td>
                                            <td><input name="campos" type="number" id="abs31"></td>
                                            <td><input name="campos" type="number" id="abs32"></td>
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

 <!--Modal COT-->
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
                                    <input type="text" class="form-control" id="observacion"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" onclick="updateObsMuestraEspectro()"
                                        id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
                                </div>
                                <div class="col-md-2">
                                <label id="ph">pH</label>
                                <input type="text" disabled class="form-control" id="phCampo">
                                    </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" onclick="guardarCOT()"><i class="voyager-upload"></i>
                                    Guardar</button>&nbsp;&nbsp;
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary"  onclick="operacionCOT()"><i class="voyager-play"></i>
                                    Ejecutar</button>
                            </div>
                            {{-- <div class="col-md-2">
                                <button class="btn btn-warning">Liberar</button>
                            </div> --}}
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

         <!-- Modal Control Calidad-->
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
                                {{-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Formula</label>
                                        <input type="text" id="mFormula" disabled value="Forms">
                                    </div>
                                </div> --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Tipo</label>
                                        <select class="form-control" id="controlCalidad">
                                            @foreach ($controlModel as $item)
                                            <option value="{{$item->Id_control}}">{{$item->Control}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" onclick="createControlCalidad()" id="guardar" class="btn btn-primary">Generar</button>
                        </div>
                  
                </div>
            </div>
        </div>

        <!-- Modal Sulfatos -->
        <div class="modal fade" id="modalCapturaSulfatos" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Captura de resultados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Observación</label>
                                    <input type="text" class="form-control" id="observacionSulfatos"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button" onclick="updateObsMuestraEspectroSulfatos()"
                                        id="btnAplicarObsSulfatos"><i class="voyager-check"></i> Aplicar</button>
                                </div>
                            </div>
                           
                            <div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="guardarSulfato"><i class="voyager-upload"></i>
                                    Guardar</button>&nbsp;&nbsp;
                            </div> 
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="ejecutarModalSulfato"><i class="voyager-play"></i>
                                    Ejecutar</button>
                            </div>
                            {{-- <div class="col-md-2">
                                <button class="btn btn-warning">Liberar</button>
                            </div> --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="resultado">Resultado</label>
                                    <input type="text" id="resultadoF" style="font-size: 20px;color:red;"
                                        placeholder="Resultado">
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
                                            <td><input type="text" id="abs1F"></td>
                                            <td><input type="text" id="abs2F"></td>
                                            <td>C</td>
                                        </tr>
                                        <tr>
                                            <td>CA</td>
                                            <td>Blanco</td>
                                            <td><input name="campos" type="number" id="blanco1F"></td>
                                            <td><input name="campos" type="number" id="blanco2F"></td>
                                            <td>F</td>
                                        </tr>
                                        <tr>
                                            <td>CB</td>
                                            <td>b</td>
                                            <td><input name="campos" type="number" id="b1F" disabled></td>
                                            <td><input name="campos" type="number" id="b2F"></td>
                                        </tr>
                                        <tr>
                                            <td>CM</td>
                                            <td>m</td>
                                            <td><input name="campos" type="number" id="m1F" disabled></td>
                                            <td><input name="campos" type="number" id="m2F"></td>
                                        </tr>
                                        <tr>
                                            <td>CR</td>
                                            <td>r</td>
                                            <td><input name="campos" type="number" id="r1F" disabled></td>
                                            <td><input name="campos" type="number" id="r2F"></td>
                                        </tr>
                                        <tr>
                                            <td>D</td>
                                            <td>Factor dilucion</td>
                                            <td><input type="number" id="fDilucion1F" disabled></td>
                                            <td><input type="number" id="fDilucion2F" disabled></td>
                                            <td>V</td>
                                        </tr>

                                        <tr>
                                            <td>E</td>
                                            <td>Vol de la muestra</td>
                                            <td><input name="campos" type="number" id="volMuestra1F" value="0"></td>
                                            <td><input name="campos" type="number" id="volMuestra2F" value="0"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS1</td>
                                            <td>Absorbancia1</td>
                                            <td><input name="campos" type="number" id="abs11F"></td>
                                            <td><input name="campos" type="number" id="abs12F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS2</td>
                                            <td>Absorbancia2</td>
                                            <td><input name="campos" type="number" id="abs21F"></td>
                                            <td><input name="campos" type="number" id="abs22F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS3</td>
                                            <td>Absorbancia3</td>
                                            <td><input name="campos" type="number" id="abs31F"></td>
                                            <td><input name="campos" type="number" id="abs32F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS4</td>
                                            <td>Absorbancia4</td>
                                            <td><input name="campos" type="number" id="abs41F"></td>
                                            <td><input name="campos" type="number" id="abs42F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS5</td>
                                            <td>Absorbancia5 </td>
                                            <td><input name="campos" type="number" id="abs51F"></td>
                                            <td><input name="campos" type="number" id="abs52F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS6</td>
                                            <td>Absorbancia6</td>
                                            <td><input name="campos" type="number" id="abs61F"></td>
                                            <td><input name="campos" type="number" id="abs62F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS7</td>
                                            <td>Absorbancia7</td>
                                            <td><input name="campos" type="number" id="abs71F"></td>
                                            <td><input name="campos" type="number" id="abs72F"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>ABS8</td>
                                            <td>Absorbancia8</td>
                                            <td><input name="campos" type="number" id="abs81F"></td>
                                            <td><input name="campos" type="number" id="abs82F"></td>
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
    @stop

    @section('javascript')
    <script src="{{asset('/public/js/laboratorio/fq/capturaEspectro.js')}}"></script>
    <script src="{{asset('/public/js/libs/funciones.js')}}"></script>
    @stop

    @endsection