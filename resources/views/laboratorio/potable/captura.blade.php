@extends('voyager::master')

@section('content')

@section('page_header')
<h6 class="page-title">
    <i class="voyager-window-list"></i>
    Captura Potable.
</h6>

@stop
<div class="container-fluid">
    <div class="row"> 
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control" name="formulaTipo" id="formulaTipo">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                    <option value={{$parametros->Id_parametro}}>({{$parametros->Id_parametro}}) {{$parametros->Parametro}} ({{$parametros->Tipo_formula}})</option> 
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
            <button class="btn btn-success" onclick="getLote()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
            <input id="idLote" hidden>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div id="divLote">
                        <table class="table" id="">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Fecha lote</th>
                                    <th>Total asignados</th>
                                    <th>Total liberados</th>
                                    <th>Opc</th>
                                </tr>
                            </thead>
                            <!-- <button class="btn btn-success" id="btnImprimir"><i class="fas fa-file-download"></i></button> -->
                            <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-2">
                            <p class="">Información global</p>
            
                        </div>
                            <div class="col-md-2">
                                <p class="">Información</p>
                                
                            </div>
                            <div class="col-md-3">
                                <textarea id="observacion"></textarea>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="enviar" onclick="enviarObsGeneral()">enviar</button>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                            id="btnGenControlInd">Generar control</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                            id="btnGenControl">Generar
                            controles</button>
                    </div>

                    <div class="col-md-1">
                        {{-- <button class="btn btn-secondary">Duplicar</button> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="divTablaControles">
                    <table class="table">
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

    </div>
</div>

        <!-- Modal Dureza-->
        <div class="modal fade" id="modalDureza" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Directo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestra"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra(1)"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
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
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                       
                                        <tr>
                                            <td>A</td>
                                            <td>Mililitros titulados de EDTA</td>
                                            <td><input type="text" id="edta1" value="0"></td>
                                            <td><input type="text" id="edta2" value="0"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>C</td>
                                            <td>pH de la muestra</td>
                                            <td><input type="text" id="ph1" value="0"></td>
                                            <td><input type="text" id="ph2" value="0"></td>
                                            <td>V</td>
                                            
                                        </tr>
                                        <tr>
                                            <td>D</td>                 
                                            <td>Volmuen total de muestra (mL)</td>                        
                                            <td><input type="text" id="vol1" value="0"></td>
                                            <td><input type="text" id="vol2" value="0"></td>
                                            <td>V</td>
                                           
                                        </tr>
                                        <tr>
                                            <td>RE</td>
                                            <td>Factor real Dureza</td>
                                            <td><input type="text" id="real1" value="0"></td>
                                            <td><input type="text" id="real2" value="0"></td>
                                            <td>F</td>
                                        </tr>
                                        <tr>
                                            <td>B</td>
                                            <td>Factor de conversión mg/L</td>
                                            <td><input type="text" id="conversion1" value="0"></td>
                                            <td><input type="text" id="conversion1" value="0"></td>
                                            <td>C</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultado" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacion(1)" id="guardar"
                            class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Potable -->
        <div class="modal fade" id="modalPotable" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Potable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestra"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra(2)"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
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
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                       
                                        <tr>
                                            <td>B</td>
                                            <td>Factor de dilución</td>
                                            <td><input type="text" id="dilucion1"></td>
                                            <td><input type="text" id="dilucion2"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>X</td>
                                            <td>Lectura 1</td>
                                            <td><input type="text" id="lectura11"></td>
                                            <td><input type="text" id="lectura12"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>Y</td>
                                            <td>Lectura 2</td>
                                            <td><input type="text" id="lectura21"></td>
                                            <td><input type="text" id="lectura22"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>Z</td>
                                            <td>Lectura 3</td>
                                            <td><input type="text" id="lectura31"></td>
                                            <td><input type="text" id="lectura32"></td>
                                            <td>V</td>
                                        </tr>
                                        <tr>
                                            <td>C</td>
                                            <td>Volumen de muestra</td>
                                            <td><input type="text" id="volM1"></td>
                                            <td><input type="text" id="volM2"></td>
                                            <td>C</td>
                                        </tr>
                                        <tr>
                                            <td>A</td>
                                            <td>Promedio</td>
                                            <td><input type="text" id="prom1"></td>
                                            <td><input type="text" id="prom2"></td>
                                            <td>C</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultadoPotable" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacion(2)" id="guardar"
                            class="btn btn-primary">Calcular</button>
                    </div>
                </div>
            </div>
        </div>


        
        <!-- Modal Potable -->
        <div class="modal fade" id="modalDurezaDif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="">Potable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="obsMuestra"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra(2)"
                                            id="btnAplicarObs"><i class="voyager-check"></i> Aplicar</button>
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
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                       
                                        <tr>
                                            <td>Dureza Magnesio</td>
                                            <td>DT - DC</td>
                                            <td><input type="number" id="durezaT" placeholder="Sin resultado"> - <input type="number" id="durezaC" placeholder="Sin resultado"></td>
                                            <td>Diferencia</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="resultado">Resultado</label>
                            <input type="text" id="resultadoDurezaM" style="font-size: 20px;color:red;"
                                placeholder="Resultado">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="operacion(3)" id="guardar"
                            class="btn btn-primary">Calcular</button>
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
                                    <button type="button" onclick="createControlCalidad()"
                                        class="btn btn-primary">Generar</button>
                                </div>
                        </div>
                    </div>
                </div>

    @section('javascript')
    <script src="{{asset('/public/js/laboratorio/potable/captura.js')}}?v=0.0.1"></script>
    @stop

    @endsection