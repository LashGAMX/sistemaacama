@extends('voyager::master')

@section('content')

@section('page_header')
<h6 class="page-title">
    <i class="voyager-window-list"></i>
    Captura de resultados Micro
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
                    <option value={{$parametros->Id_parametro}}>{{$parametros->Parametro}}</option>
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
            <button class="btn btn-success" onclick="getLoteMicro()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-3">
            <input id="idLote" hidden>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
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
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                            <div id="infoGlobal">

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

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-1">
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

        <!-- Modal -->
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
                        <button type="button" onclick="createControlCalidad()" id="guardar"
                            class="btn btn-primary">Generar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCapturaCol" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="width: 80%;">
                <div class="modal-content">
                    <form >
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Captura coliformes</h5>
                            <input hidden type="text" id="indicador" value="">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="observacionCol"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra(1,'observacionCol')"
                                            id="btnAplicarObsColiformes"><i class="voyager-check"></i> Aplicar</button>
                                    </div>
                                    <div class="col-md-3">
                                    
                                    <button type="button" id="metodoCortoCol"> <i class="voyager-window-list"></i></button>
                                    </div>
                                    <div class="col-md-3">
                                    
                                    <button type="button" id="limpiar" class="btn btn-success" class="voyager-fa-file-o" >Limpiar</button>
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
                                                <td>D1 | D2 | D3</td>
                                                <td>Diluciones</td>
                                                <td>
                                                    <input type="text" id="dil1" value="0" style="width: 60px;">
                                                    <input type="text" id="dil2" value="0" style="width: 60px;">
                                                    <input type="text" id="dil3" value="0" style="width: 60px;">
                                                </td>
                                                <td>
                                                    <input type="text" id="dil12" value="0" style="width: 60px;">
                                                    <input type="text" id="dil22" value="0" style="width: 60px;">
                                                    <input type="text" id="dil32" value="0" style="width: 60px;">
                                                </td>
                                                <td>V</td>
                                            </tr>
                                            <tr>
                                                <td>NMP</td>
                                                <td>Indice NMP</td>
                                                <td><input type="text" id="nmp1" value="0"></td>
                                                <td><input type="text" id="nmp2" value="0"></td>
                                                <td>V</td>
                                            </tr>
                                            <tr>
                                                <td>G3</td>
                                                <td>mL De muestra en todos los tubos</td>
                                                <td><input type="text" id="todos1" value="0"></td>
                                                <td><input type="text" id="todos2" value="0"></td>
                                                <td>V</td>
                                            </tr>
                                            <tr>
                                                <td>G2</td>
                                                <td>mL De muestra en tubos negativos</td>
                                                <td><input type="text" id="negativos1" value="0"></td>
                                                <td><input type="text" id="negativos2" value="0"></td>
                                                <td>V</td>
                                            </tr>
                                            <tr>
                                                <td>G1</td>
                                                <td># de tubos positivos</td>
                                                <td><input type="text" id="positivos1" value="0"></td>
                                                <td><input type="text" id="positivos2" value="0"></td>
                                                <td>V</td>
                                            </tr>
                                            <tr>
                                                <td>P1 - P9</td>
                                                <td>Prueba presuntiva</td>
                                                <td>
                                                    <input type="text" id="pre1" value="0" style="width: 60px;">
                                                    <input type="text" id="pre4" value="0" style="width: 60px;">
                                                    <input type="text" id="pre7" value="0" style="width: 60px;">
                                                    <br>
                                                    <input type="text" id="pre2" value="0" style="width: 60px;">
                                                    <input type="text" id="pre5" value="0" style="width: 60px;">
                                                    <input type="text" id="pre8" value="0" style="width: 60px;">
                                                    <br>
                                                    <input type="text" id="pre3" value="0" style="width: 60px;">
                                                    <input type="text" id="pre6" value="0" style="width: 60px;">
                                                    <input type="text" id="pre9" value="0" style="width: 60px;">
                                                </td>
                                                <td>
                                                    <input type="text" id="pre12" value="0" style="width: 60px">
                                                    <input type="text" id="pre42" value="0" style="width: 60px">
                                                    <input type="text" id="pre72" value="0" style="width: 60px">
                                                    <br>
                                                    <input type="text" id="pre22" value="0" style="width: 60px">
                                                    <input type="text" id="pre52" value="0" style="width: 60px">
                                                    <input type="text" id="pre82" value="0" style="width: 60px">
                                                    <br>
                                                    <input type="text" id="pre32" value="0" style="width: 60px">
                                                    <input type="text" id="pre62" value="0" style="width: 60px">
                                                    <input type="text" id="pre92" value="0" style="width: 60px">
                                                </td>
                                                <td>V</td>
                                            </tr> 
                                            <tr>
                                                <td>C1 - C9</td>
                                                <td>Prueba confirmativa</td>
                                                <td>
                                                    <input type="text" id="con1" value="0" style="width: 60px;">
                                                    <input type="text" id="con4" value="0" style="width: 60px;">
                                                    <input type="text" id="con7" value="0" style="width: 60px;">
                                                    <br>
                                                    <input type="text" id="con2" value="0" style="width: 60px;">
                                                    <input type="text" id="con5" value="0" style="width: 60px;">
                                                    <input type="text" id="con8" value="0" style="width: 60px;">
                                                    <br>
                                                    <input type="text" id="con3" value="0" style="width: 60px;">
                                                    <input type="text" id="con6" value="0" style="width: 60px;">
                                                    <input type="text" id="con9" value="0" style="width: 60px;">
                                                </td>
                                                <td>
                                                    <input type="text" id="con12" value="0" style="width: 60px">
                                                    <input type="text" id="con42" value="0" style="width: 60px">
                                                    <input type="text" id="con72" value="0" style="width: 60px">
                                                    <br>
                                                    <input type="text" id="con22" value="0" style="width: 60px">
                                                    <input type="text" id="con52" value="0" style="width: 60px">
                                                    <input type="text" id="con82" value="0" style="width: 60px">
                                                    <br>
                                                    <input type="text" id="con32" value="0" style="width: 60px">
                                                    <input type="text" id="con62" value="0" style="width: 60px">
                                                    <input type="text" id="con92" value="0" style="width: 60px">
                                                </td>
                                                <td>V</td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="resultado">Resultado</label>
                                        <input type="text" id="resultadoCol" style="font-size: 20px;color:red;"
                                            placeholder="Resultado">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="operacionCol();">Guardar y
                                ejecutar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    <!-- Modal -->
    <div class="modal fade" id="modalCapturaHH" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Captura HH</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Observación</label>
                                    <input type="text" class="form-control" id="observacionHh"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button"
                                        onclick="updateObsMuestra(3,'observacionHh')" id=""><i
                                            class="voyager-check"></i> Aplicar</button>
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
                                    <!-- <button class="btn btn-success" id="btnImprimir" onclick="imprimir();"><i class="fas fa-file-download"></i></button> -->
                                    <tbody>
                                        <tr>
                                            <td>A</td>
                                            <td>A. lumbicoides</td>
                                            <td><input type="text" id="lum1" value="0"></td>
                                            <td><input type="text" id="lum2" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>C</td>
                                            <td>H. NANA</td>
                                            <td><input type="text" id="na1" value="0"></td>
                                            <td><input type="text" id="na2" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>E</td>
                                            <td>TAENIA SP</td>
                                            <td><input type="text" id="sp1" value="0"></td>
                                            <td><input type="text" id="sp2" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>D</td>
                                            <td>T. Trichiura</td>
                                            <td><input type="text" id="tri1" value="0"></td>
                                            <td><input type="text" id="tri2" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>B</td>
                                            <td>Unicinarias</td>
                                            <td><input type="text" id="uni1" value="0"></td>
                                            <td><input type="text" id="uni2" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td>G</td>
                                            <td>Vol. Muestra</td>
                                            <td><input type="text" id="volH1" value="0"></td>
                                            <td><input type="text" id="volH" value="0"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="resultado">Resultado</label>
                                    <input type="text" id="resultadoHH" style="font-size: 20px;color:red;"
                                        placeholder="Resultado">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="operacionHH()">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalCapturaDboBlanco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form >
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
                                    <input type="text" class="form-control" id="observacionDbo"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    {{-- <button class="btn btn-success" type="button"onclick="updateObsMuestra(2,'observacionDbo')"><i class="voyager-check"></i>Aplicar</button> --}}
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="resultado">Resultado</label>
                                        <input type="text" id="resDboB" style="font-size: 20px;color:red;" placeholder="Resultado">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="operacionDbo(2)">Guardar</button>
                        </div>
                        
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modalCapturaDbo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form>
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
                                    <input type="text" class="form-control" id="observacionDbo"
                                        placeholder="Observacion de la muestra">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success" type="button"
                                        onclick="updateObsMuestra(2,'observacionDbo')" id="btnAplicarObsColiformes"><i
                                            class="voyager-check"></i> Aplicar</button>
                                </div>
                                <div class="col-md-12">
                                    <h4>Resultado DQO <input type="text" id="resDqo" disabled></h4>
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
                                                <td>H</td>
                                                <td>No De botella final</td>
                                                <td><input type="text" id="botellaF1" value="0"></td>
                                                <td><input type="text" id="botellaF2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>G</td>
                                                <td>No De botella Od</td>
                                                <td><input type="text" id="od1" value="0"></td>
                                                <td><input type="text" id="od2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>B</td>
                                                <td>Oxigeno disuelto final</td>
                                                <td><input type="text" id="oxiFinal1" value="0"></td>
                                                <td><input type="text" id="oxiFinal2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>A</td>
                                                <td>Oxigeno disuelto inicial</td>
                                                <td><input type="text" id="oxiInicial1" value="0"></td>
                                                <td><input type="text" id="oxiInicial2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>J</td>
                                                <td>pH Final</td>
                                                <td><input type="text" id="phF1" value="0"></td>
                                                <td><input type="text" id="phF2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>I</td>
                                                <td>ph Inicial</td>
                                                <td><input type="text" id="phIni1" value="0"></td>
                                                <td><input type="text" id="phIni2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>D</td>
                                                <td>Volumen de muestra</td>
                                                <td><input type="text" id="volDbo1" value="0"></td>
                                                <td><input type="text" id="volDbo2" value="0"></td>
                                                <th>V</th>
                                            </tr>
                                            <tr>
                                                <td>E</td>
                                                <td>% dilucion (DBO5)</td>
                                                <td><input type="text" id="dil1" value="0"></td>
                                                <td><input type="text" id="dil2" value="0"></td>
                                                <th>C</th>
                                            </tr>
                                            <tr>
                                                <td>C</td>
                                                <td>Vol botella winkler</td>
                                                <td><input type="text" id="win1" value="300"></td>
                                                <td><input type="text" id="win2" value="300"></td>
                                                <th>C</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="resultado">Resultado</label>
                                        <input type="text" id="resDbo" style="font-size: 20px;color:red;"
                                            placeholder="Resultado">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="operacionDbo(1)">Ejecutar y Guardar</button>
                        </div>
                </form>
            </div>
        </div>
    </div>



    @section('javascript')
    <script src="{{asset('/public/js/laboratorio/mb/captura.js')}}"></script>
    @stop

    @endsection