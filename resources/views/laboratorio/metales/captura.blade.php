@extends('voyager::master')

@section('content')

@section('page_header')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleFormControlSelect1">Fórmula tipo</label>
                <select class="form-control select2" name="formulaTipo" id="formulaTipo" onchange="getUltimoLote()">
                    <option value="0">Sin seleccionar</option>
                    @foreach ($parametro as $parametros)
                    <option value="{{$parametros->Id_parametro}}">
                        ({{$parametros->Id_parametro}}){{$parametros->Parametro}} ({{$parametros->Tipo_formula}})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="">Folio</label>
                <input type="text" style="width: " class="form-control" id="folio" placeholder="xxx-xx/xx-x">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control" id="fechaAnalisis">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" onclick="getDataCaptura()" id="btnBuscar">Buscar</button>
        </div>
        <div class="col-md-2" id="divUltimoLote">

        </div>
        <div class="col-md-3">
            <input id="idLote" hidden>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div id="divLote">
                        <table class="table" id="tablaLote">
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
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                    <p class="">Observacion Metales</p>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h6>B <input placeholder="B" id="b"></h6>
                                <h6>M <input placeholder="M" id="m"></h6>
                                <h6>R <input placeholder="R" id="r"></h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Observaciones</label>
                                <textarea class="form-control" id="observacion"
                                    style="width: 300px;height: 60px"></textarea>
                                <button class="btn btn-success" id="enviarObservacion">Aplicar</button>
                            </div>
                        </div>

<<<<<<< HEAD
                    </div>
=======
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-success" id="btnEjecutarTodo">Ejecutar T.</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="btnLiberarTodo">Liberar Todo</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-danger" id="btnEliminarControl"><i class="fas fa-trash"></i> Ctrl</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-warning" id="btnDesliberar"><i class="fas fa-trash"></i> Ctrl</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                        id="btnGenControlInd">Generar control</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="btnGenerarControles">Generar controles</button>
                </div>
                <div class="col-md-2">
                    <h2><input placeholder="Resultado" style="color:red" id="resDato"></h2>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="divTablaControles">
                <table class="table" id="tablaControles">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NumMuestra</th>
                            <th>NomCliente</th>
                            {{-- <th>PuntoMuestreo</th> --}}
                            <th>Vol. Muestra E</th>
                            <th>Abs1</th>
                            <th>Abs2</th>
                            <th>Abs3</th>
                            <th>Absorción
                                promedio
                            </th>
                            <th>Factor dilución D</th>
                            <th>Factor conversion G</th>
                            <th>Resultado</th> 
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
      </div>
>>>>>>> 2b914187672a51c20e1918251d5136fec63fe60b

                </div>

            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="ejecutar">Ejecutar</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success" id="btnEjecutarTodo">Ejecutar T.</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-secondary" id="btnLiberar">Liberar</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="btnLiberarTodo">Liberar Todo</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger" id="btnEliminarControl"><i class="fas fa-trash"></i>
                            Ctrl</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-warning" id="btnDesliberar"><i class="fas fa-trash"></i> Ctrl</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#modalCalidad"
                            id="btnGenControlInd">Generar control</button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" id="btnGenerarControles">Generar controles</button>
                    </div>
                    <div class="col-md-2">
                        <h2><input placeholder="Resultado" style="color:red" id="resDato"></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="divTablaControles">
                    <table class="table" id="tablaControles">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NumMuestra</th>
                                <th>NomCliente</th>
                                {{-- <th>PuntoMuestreo</th> --}}
                                <th>Vol. Muestra E</th>
                                <th>Abs1</th>
                                <th>Abs2</th>
                                <th>Abs3</th>
                                <th>Absorción
                                    promedio
                                </th>
                                <th>Factor dilución D</th>
                                <th>Factor conversion G</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Control Calidad-->
        <div class="modal fade" id="modalCalidad" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="create">
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
                            <button type="button" onclick="createControlCalidad()" id="guardar"
                                class="btn btn-primary">Generar</button>
                        </div>
                    </form>
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
    </div>
    @stop
    <style>
        .rainbow-spinner {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid transparent;
            border-top: 5px solid red;
            border-right: 5px solid orange;
            border-bottom: 5px solid yellow;
            border-left: 5px solid green;
            animation: spin 1s linear infinite, rainbow 3s linear infinite;
        }


        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Animación de cambio de colores (EL ARCOIRIS)*/
        @keyframes rainbow {
            0% {
                border-top-color: red;
                border-right-color: orange;
                border-bottom-color: yellow;
                border-left-color: green;
            }

            25% {
                border-top-color: orange;
                border-right-color: yellow;
                border-bottom-color: green;
                border-left-color: blue;
            }

            50% {
                border-top-color: yellow;
                border-right-color: green;
                border-bottom-color: blue;
                border-left-color: purple;
            }

            75% {
                border-top-color: green;
                border-right-color: blue;
                border-bottom-color: purple;
                border-left-color: red;
            }

            100% {
                border-top-color: red;
                border-right-color: orange;
                border-bottom-color: yellow;
                border-left-color: green;
            }
        }
    </style>


    <!-- Modal -->
    <div class="modal fade" id="modalImgFoto" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 80%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="spinner"
                                style="display: none; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                                <div class="rainbow-spinner"></div>
                            </div>
                            <div id="divImagen"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @section('javascript')
    <script src="{{asset('public/js/laboratorio/metales/captura.jsx')}}?v=1.1.8"></script>
    @stop

    @endsection