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

        <a href="http://sistemasofia.ddns.net:85/sofia/public/storage/Recursos/MuestreoApp_v2.0.3.apk"><button class="btn btn-primary">DESCARGAR</button></a>            
        
    </div>   

    <br>

    <div class="col-md-12">
        <!-- <code>
            Nota: Borrar datos de la aplicación despues de actualizar para no tener problemas en la app! También puedes desistalar la versión anteriror.
        </code> -->

            </div>

    <div class="col-md-12">
        <code>
        <p>Mejoras: </p>
        <p>- Descarga la ultima version directamente de la app</p>
        <p>- Animación de carga al sincronizar lista de Solicitudes</p>
        <p>- Limpia la lista cuando se hayan mandado los datos y vuelve a sincronizar para mostrar las solicitudes pendientes.</p>
        
        </code>
        
        
    </div>


    <br>
    <br>

    <div class="row w-100 align-items-center">
        <div class="col text-center">
            <div style="p font-size:100%"></div>
            <p style="color: blue">Instrucciones para instalar la App Móvil</p>
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