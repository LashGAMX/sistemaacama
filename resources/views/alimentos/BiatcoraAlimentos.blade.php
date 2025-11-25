@extends('voyager::master')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4> Fechas de Las Bitacoras a Consultar</h4>
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
                        <div class="text-center mb-3">
                            <button class="btn btn-info" id="btnimprimir">Imprimir Bitacora</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h2>Bitacora de Recepcion Alimentos</h2>
                </div>
                <div class="card-body">
                    <table class="table" id="recepcionalimentos">
                        <thead>
                            <tr>
                                <th colspan="4" style="background-color: #d6d6d6; text-align: center;">Recepción de
                                    Muestras</th>
                                <th colspan="3" style="background-color: #cce5ff; text-align: center;">Recepción de Área
                                    Alimentos</th>
                                <th colspan="4" style="background-color: #fff3cd; text-align: center;">Resguardo de
                                    Alimentos (DA)</th>
                                <th colspan="3" style="background-color: #f8d7da; text-align: center;">Desecho de
                                    Muestra</th>
                                    <th colspan="2" style="background-color: #fd8fd8; text-align: center;">Adicionales</th>
                            </tr>
                            <tr>
                               
                                <th style="background-color: #d6d6d6; text-align: center;">Folio</th>
                                <th style="background-color: #d6d6d6; text-align: center;">Muestra</th>
                                <th style="background-color: #d6d6d6; text-align: center;">Recepción</th>
                                <th style="background-color: #d6d6d6; text-align: center;" >Hora recepción</th>

                               
                                <th style="background-color: #cce5ff; text-align: center;">Hora en Área</th>
                                <th style="background-color: #cce5ff; text-align: center;">Recibió en Área</th>
                                <th style="background-color: #cce5ff; text-align: center;">Resguardo En</th>

                                
                                <th style="background-color: #fff3cd; text-align: center;">Analizo </th>
                                <th style="background-color: #fff3cd; text-align: center;">Fecha de Inicio </th>
                                <th style="background-color: #fff3cd; text-align: center;">Fecha de Resguardo </th>
                                <th style="background-color: #fff3cd; text-align: center;">Lugar Resg</th>

                               
                                <th style="background-color: #f8d7da; text-align: center;">Analista Desecha</th>
                                <th style="background-color: #f8d7da; text-align: center;">Fecha de Desecho</th>
                                <th style="background-color: #f8d7da; text-align: center;">Lugar de Desecho</th>
                                <th style="background-color: #fd8fd8; text-align: center;">Fecha Muestreo</th>
                                <th style="background-color: #fd8fd8; text-align: center;">Horas Pasadas</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="{{ asset('public/js/alimentos/bitacoralimento.jsx')}}?v=0.0.1"></script>
@endsection