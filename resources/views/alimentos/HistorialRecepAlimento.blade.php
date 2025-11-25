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
                        <!-- <div class="text-center mb-3">
                            <button class="btn btn-info" id="btnimprimir">Imprimir Bitacora</button>
                        </div> -->
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h2>Bitacora de Recepcion Alimentos Real</h2>
                </div>
                <div class="card-body">
                    <table class="table" id="recepcionalimentos">
                        <thead>
                            <tr>
                                <th colspan="7" style="background-color: #d6d6d6; text-align: center;">Recepción de
                                    Muestras Real en Aliemento </th>
                                
                            </tr>
                            <tr>
                               
                                <th style="background-color: #d6d6d6; text-align: center;">Folio</th>
                                <th style="background-color: #d6d6d6; text-align: center;">Muestra</th>
                                <th style="background-color: #d6d6d6; text-align: center;">Recepción</th>
                                <th style="background-color: #d6d6d6; text-align: center;" >Hora Recepción</th>
                                <th style="background-color: #d6d6d6; text-align: center;" >Fecha y Hora en Area</th>
                                <th style="background-color: #d6d6d6; text-align: center;" >Hora Real de Ingreso</th>
                                <th style="background-color: #d6d6d6; text-align: center;" >Hora Real en Area alimento</th>
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
<script src="{{ asset('public/js/alimentos/Historial.jsx')}}?v=0.0.1"></script>
@endsection