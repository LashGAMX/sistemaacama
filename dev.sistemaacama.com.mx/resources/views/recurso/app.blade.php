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
        <a href="https://sistemaacama.com.mx/public/storage/Recursos/MuestreoApp_v0.1.apk"><button class="btn btn-primary">DESCARGAR</button></a>            
    </div>   
    
    <br>
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