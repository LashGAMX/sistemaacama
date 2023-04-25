@extends('voyager::master')

@section('content')

  @section('page_header')
  <h1>Seguimiento de muestra</h1>
 
  @stop

  <table>
 <tr>
   <td>
     <a href="#" id="primera" class="as">
       <span>muestreo</span>
       <div class="liquid"></div>
     </a>
   </td>
   <td>
     <a href="#" id="segunda" class="as">
       <span>recepción</span>
       <div class="liquid"></div>
     </a>
   </td>
   <td>
     <a href="#" id="tercera" class="as">
       <span>análisis</span>
       <div class="liquid"></div>
     </a>
   </td>
   <td>
     <a href="#" id="cuarta" class="as">
       <span>reporte</span>
       <div class="liquid"></div>
   </td>
 </tr>
  </table>
 
  

@endsection
 
@section('css')
    <link rel="stylesheet" href="{{ asset('/public/css/seguimiento/seguimiento.css')}}">
@endsection
 