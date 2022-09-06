@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-data"></i>
    Asignar muestra / Lote: {{$idLote}}
    <input type="text" id="idLote" value="{{$idLote}}" hidden>
  </h6>
 
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row">   

      </div>
    </div>
    <div class="col-md-6">
      <center><h5>Muestras sin asignar</h5></center>
      &nbsp;&nbsp;<i  id="btnAddMuestra" class="fas fa-plus text-success"></i> &nbsp;&nbsp; <i class="fas fa-paper-plane" id="sendMuestras"></i>
      <div class="" id="divTable">
          <table class="table" id=""> 
            <thead>
              <tr>
                <th>Folio</th>
                <th>Parametros</th> 
              </tr>
            </thead>
            <tbody>
             
            </tbody>
          </table>
        </div>
    </div>
    <div class="col-md-6">
      <center><h5>Muestras asignadas</h5></center>
      <div class="" id="divTable2">
        <table class="table" id=""> 
          <thead>
            <tr>
              <th>Folio</th>
              <th>Parametros</th>
            </tr>
          </thead>
          <tbody>
     
          </tbody>
        </table>
        </div>
    </div>
    
    <div class="col-md-12">
    
   
    </div>
  </div>
</div>
 

  @stop

  @section('javascript')
  <script src="{{asset('public/js/laboratorio/fq/asignarMuestraLoteVol.js')}}"></script>

  @stop

@endsection