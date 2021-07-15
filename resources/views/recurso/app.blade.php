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
        <div class="row w-100 align-items-center">
            <div class="col text-center">
                <a href="https://dev.sistemaacama.com.mx//storage/Recursos/app-debug.apk"><button class="btn btn-primary">DESCARGAR</button></a>
            </div>
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