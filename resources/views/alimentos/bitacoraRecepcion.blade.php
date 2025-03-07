@extends('voyager::master')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4>Selecciona un Rango de Fechas de Las Bitacoras a Consultar</h4>
                    </div>
                    <div class="form-group text-center">
                        <div class="row mb-2 justify-content-center">
                            <!-- Fecha de Inicio -->
                            <div class="col-4 col-md-2">
                                <label for="Finicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" id="Finicio" class="form-control mx-auto" style="max-width: 100%;">
                            </div>

                            <!-- Fecha de Fin -->
                            <div class="col-4 col-md-2">
                                <label for="Fin" class="form-label">Fecha de Fin</label>
                                <input type="date" id="Fin" class="form-control mx-auto" style="max-width: 100%;">
                            </div>
                        </div>

                        <div class="text-center mb-3">
                            <button class="btn btn-success" id="btnbuscarbitacora">Buscar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4>Datos de la Bitacora de Recepción</h4>
                    </div>
                    <table id="DataBitacora">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Hora_recepcion</th>
                                <th>Hora_entrada</th>
                                <th>QUIEN dio Entrada</th>
                                <th>Muestra</th>
                                <th>Fecha_muestreo</th>
                                <th>Id_direcion a</th>
                                <th>Atención</th>
                                <th>Norma</th>
                                <th>Parametros</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </div>
    @endsection

    @section('javascript')
    <script src="{{ asset('public/js/alimentos/bitacoraRecepcion.jsx')}}?v=0.0.1"></script>
    @endsection