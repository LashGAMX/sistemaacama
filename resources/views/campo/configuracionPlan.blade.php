@extends('voyager::master')

@section('content')

  @section('page_header')
 
  @stop

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <h6>Paquetes</h6>
        <div id="divTablePaquetes" class="card">
          <table id="tablePaquetes" class="display compact" style="width:100%">
            <thead>
                <tr>
                  <th>Paquete</th>
                  <th>Norma</th>
                  <th>Clave</th>
                  <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
  
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-6">
        <h6>Plan muestreo</h6>
        &nbsp;&nbsp;<i  id="btnAddPlan" class="fas fa-plus text-success"></i>
        <div id="divTableEnvase" class="card">
          <table id="tableEnvase" class="display compact" style="width:100%">
            <thead>
                <tr>
                  <th>Analisis</th>
                  <th>Cantidad</th>
                  <th>Recipiente</th>
                </tr>
            </thead>
            <tbody>
  
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <h6>Material de medición</h6>
        &nbsp;&nbsp;<i  id="btnAddMaterial" class="fas fa-plus text-success"></i>
        <div id="divTableMaterial" class="card">
          <table id="tableMaterial" class="display compact" style="width:100%">
            <thead>
                <tr>
                  <th>Material</th
                </tr>
            </thead>
            <tbody>
  
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <h6>Equipo Muestreo/Seguridad</h6>
        &nbsp;&nbsp;<i  id="btnAddEquipo" class="fas fa-plus text-success"></i>
        <div id="divTableEquipo" class="card">
          <table id="tableEquipo" class="display compact" style="width:100%">
            <thead>
                <tr>
                  <th>Equipo</th
                </tr>
            </thead>
            <tbody>
  
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <h6>Complementos</h6>
        &nbsp;&nbsp;<i  id="btnAddComplemento" class="fas fa-plus text-success"></i>
        <div id="divTableComplementoCamp" class="card">
          <table id="tableComplementoCamp" class="display compact" style="width:100%">
            <thead>
                <tr>
                  <th>Complemento</th 
                </tr>
            </thead>
            <tbody>
  
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


  <!-- Inicio  de modal -->
<div id="divModal">

</div>

@endsection  


@section('javascript')
    <script src="{{asset('/public/js/campo/configuracionPlan.js')}}?v=0.0.1"></script>
@stop  
 
 