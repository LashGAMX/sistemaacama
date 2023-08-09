@extends('voyager::master')

@section('content')

@section('page_header')
    <div class="row">
        <h6 class="page-title">            
            <i class="fas fa-mobile-alt"></i>
            App Móvil
        </h6>
    </div>    

    <div class="container-fluid h-100">         
        <a href="http://acama.ddns.net:85/sofia/public/storage/MuestreoApp_v2.1.apk"><button class="btn btn-primary">DESCARGAR</button></a>            
    </div>   

    <br>

    <div class="col-md-12">
        <code>
            Nota: Borrar datos de la aplicación despues de actualizar para no tener problemas en la app! También puedes desistalar una versión anteriror.
        </code>

            </div>
   <br>
   <br>
    <div class="col-md-12">
        <p>Mejoras: </p>
        <p>Ahora puedes escoger cualquer modelo de termometro PC100 Y HANNA. Catalogos actualizados y separados.</p>
        <p>En "Generales" ya se actualizan los selectores de "ph Trazable" y "pH Calidad" </p>
    </div>


    <br>
    <br>

    <div class="row w-100 align-items-center">
        <div class="col text-center">
            <div style="p font-size:100%"></div>
            <p style="color: red">Instrucciones para instalar la App Móvil</p>
            <video src="https://sistemaacama.com.mx/public /storage/Recursos/Instrucciones_App.mp4" width="640" height="480" controls autoplay></video>
        </div>
    </div>
@stop

<div>
    {{-- Be like water. --}}

    <div class="page-content">
        <div class="alerts">
        </div>
        <div class="clearfix container-fluid row">
                               
        </div>
        {{-- <livewire:historial.config/> --}}
    @endsection