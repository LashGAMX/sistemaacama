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
                    <table id="DataBitacora" class="table">
                        <thead>
                            <tr>
                                <th colspan="6" style="background-color: #f5c6cb; text-align: center;">Solicitud</th>
                                <th colspan="13" style="background-color: #bee5eb; text-align: center;">Recepción</th>
                            </tr>
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Atención</th>
                                <th>Norma</th>
                                <th>Muestra</th>
                                <th>Recibio</th>
                                <th>Hora Recep</th>
                                <th>Hora Entrada</th>
                                <th>Fecha Muestreo</th>
                                <th>Cant.</th>
                                <th>Ud.</th>
                                <th>Tem. Recep</th>
                                <th>Tem. Muestra</th>
                                <th>Obs</th>
                                <th>Motivo</th>
                                <th>Cumple</th>
                                <th>Parametros</th>
                                <th>Calculo TRI</th>
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