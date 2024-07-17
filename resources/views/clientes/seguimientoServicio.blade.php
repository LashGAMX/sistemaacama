<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Seguimiento Servicio</title>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{asset('public/css/clientes/seguimientoServicio.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center mb-3">
            <div class="col-11 col-xxl-10 fondo-blanco rounded-4 shadow-lg p-5 my-4">
                <div class="row mb-4 mb-xxl-3">
                    <div class="col text-center">
                        <img src="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Acama_Imagotipo.png" alt="Logo Acama" width="199px" height="63.875px">
                    </div>
                </div>
                <div class="row mb-3 mb-xxl-0">
                    <div class="col">
                        <h6 class="display-6">¡Hola<span class="texto-verde" id="nombre-cliente"></span>!</h6>
                    </div>
                </div>
                <div class="row mb-3 mb-xxl-0">
                    <div class="col">
                        <p><span class="texto-verde">Pronto tendrás los resultados</span> de tu muestra, pero por el momento puedes ver el progreso</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <p>Ingresar el número de seguimiento</p>
                        <div class="row">
                            <div class="col-10 col-md-6 col-xl-3">
                                <input type="text" class="form-control" id="numero-seguimiento">
                                <small class="error" id="error-numero-seguimiento"></small> <!-- No se encontró ninguna muestra -->
                            </div>
                            <div class="mb-3 d-md-none"></div>
                            <div class="col">
                                <button type="button" class="btn btn-success" id="buscar-numero-seguimiento">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-3">
            <div class="col-11 col-xxl-10 fondo-blanco rounded-4 shadow-lg p-5 my-4">
                <div class="row mb-3">
                    <div class="col text-center">
                        <h6 class="display-6">Progreso</h6>
                    </div>
                </div>
                <div id="contenido">
                    <div class="row justify-content-center mb-5">
                        <div class="col-12 col-md-10">
                            <div class="position-relative">
                                <div class="progress" role="progressbar" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%" id="barra-progreso"></div>
                                </div>
                                <div id="contenedor-iconos">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-md-5 pb-3">
                        <div class="col">
                            <details>
                                <summary class="deshabilitado" id="orden-servicio">Orden de servicio</summary>
                                <div>
                                    <p>Cliente: <span id="cliente"></span></p> <!-- SISTEMA OPERADOR DE LOS SERVICIOS DE AGUA POTABLE Y ALCANTARILLADO DEL MUNICIPIO DE PUEBLA (SOAPAP) -->
                                    <p>Norma : <span id="norma"></span></p> <!-- NOM-002-SEMARNAT-1996 -->
                                    <p>Servicio : <span id="servicio"></span></p> <!-- Análisis y muestreo -->
                                    <p>Siralab: <span id="siralab"></span></p> <!-- No -->
                                    <p>Folio : <span id="folio"></span></p> <!-- 152-23/24 -->
                                </div>
                            </details>
                        </div>
                    </div>
                    <div class="row pb-3">
                        <div class="col">
                            <details>
                                <summary class="deshabilitado" id="muestreo">Muestreo</summary>
                                <div>
                                    <p id="punto"></p> <!-- //Capturando puntos de muestreo - Puntos de muestreo capturados -->
                                </div>
                            </details>
                        </div>
                    </div>
                    <div class="row pb-3">
                        <div class="col">
                            <details>
                                <summary class="deshabilitado" id="recepcion">Recepción</summary>
                                <div>
                                    <p id="texto-recepcion"></p> <!-- 2024-05-31 18:00:00 -->
                                </div>
                            </details>
                        </div>
                    </div>
                    <div class="row pb-3">
                        <div class="col">
                            <details>
                                <summary class="deshabilitado" id="ingreso-lab">Ingreso al lab</summary>
                                <div>
                                    <p id="estado-muestra"></p> <!-- Muestras ingresadas - Analizando muestras - Muestras analizadas -->
                                </div>
                            </details>
                        </div>
                    </div>
                    <div class="row pb-3">
                        <div class="col">
                            <details>
                                <summary class="deshabilitado" id="impresion">Impresión</summary>
                                <div>
                                    <p id="estado-impresion"></p> <!-- Informe pendiente por impresion - Informe impreso -->
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{asset('public/js/cliente/seguimientoServicio.js')}}"></script>
</body>
</html>