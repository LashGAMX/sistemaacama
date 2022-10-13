@extends('voyager::master')

@section('content')

@section('page_header')
<h6 class="page-title">
    <i class="voyager-window-list"></i>
    Captura Directos.
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
                    <option value={{$parametros->Id_parametro}}>{{$parametros->Id_parametro}}/{{$parametros->Parametro}} / {{$parametros->Tipo_formula}}</option> 
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
        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <label for="">Observación</label>
                                        <input type="text" class="form-control" id="observacionCol"
                                            placeholder="Observacion de la muestra">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="button"
                                            onclick="updateObsMuestra(1,'observacionCol')"
                                            id="btnAplicarObsColiformes"><i class="voyager-check"></i> Aplicar</button>
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
                                            <td><input type="text" id="lecturaUno1" value="0"></td>
                                            <td><input type="text" id="lecturaUno2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L2.</td>
                                            <td><input type="text" id="lecturaDos1" value="0"></td>
                                            <td><input type="text" id="lecturaDos2" value="0"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>L3.</td>                                         
                                            <td><input type="text" id="lecturaTres1" value="0"></td>
                                            <td><input type="text" id="lecturaTres2" value="0"></td>
                                           
                                        </tr>
                                        <tr>
                                            <td>Temperatura.</td>
                                            <td><input type="text" id="temperatura1" value="0"></td>
                                            <td><input type="text" id="temperatura2" value="0"></td>
                                          
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
                        <button type="button" onclick="operacion()" id="guardar"
                            class="btn btn-primary">Generar</button>
                    </div>
                </div>
            </div>
        </div>


    @section('javascript')
    <script src="{{asset('/public/js/laboratorio/directos/captura.js')}}"></script>
    @stop

    @endsection