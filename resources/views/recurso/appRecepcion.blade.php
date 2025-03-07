@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">            
            <i class="fas fa-mobile-alt"></i>
            App Móvil
        </h6>
    </div>
@stop
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="http://sistemasofia.ddns.net:85/sofiadev/public/storage/Recursos/RecepcionApp_v1-1-0.apk"><button class="btn btn-primary">DESCARGAR</button></a>
                <p>Última versión: 1.1.0</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Log de cambios</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5>v1.1.0 (29/08/2024)</h5>
                <p>- Mostrar errores al fallar comunicación con el servidor</p>
                <p>- Arreglar error en el que al subir una foto antes que cargaran las anteriores, desaparecía la última foto</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5>v1.0.0 (27/08/2024)</h5>
                <p>- Generar códigos del folio</p>
                <p>- Introducción de generar códigos sin condicones</p>
                <p>- Ingresar muestras</p>
                <p>- Introducción de ingresar muestras por historial</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5>v0.1.0 Beta (22/08/2024)</h5>
                <p>- Busqueda de folios</p>
                <p>- Ver información de folios</p>
                <p>- Ver información de puntos de muestreo</p>
                <p>- Tomar fotos a puntos de muestreo</p>
            </div>
        </div>
    </div>

@endsection