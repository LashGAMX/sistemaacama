@extends('voyager::master')

@section('content')
 
  <div class="container-fluid">

    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-2">
            <label for="tipoFormula">Tipo fórmula</label>                
            <select class="form-control" id="tipoFormula">
              @foreach($formulas as $formula)
                <option value="{{$formula->Id_tipo_formula}}">{{$formula->Tipo_formula}}</option>
              @endforeach 
            </select>         
          </div>
          <div class="col-md-1">
            <br><button class="btn btn-info" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
          </div>
          <div class="col-md-3">
            <label for="exampleFormControlSelect1">PH < 2</label>
            <select class="form-control" id="ph">
              <option value="Sin seleccionar">Sin seleccionar</option>
              <option value="SI" selected>SI</option>
              <option value="NO">NO</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="exampleFormControlTextarea1">Observaciones</label>
            <textarea class="form-control" id="obs" rows="3"></textarea>
          </div>
          <div class="col-md-2">
            <br><button class="btn btn-success" id="btnAplicar" onclick="aplicar();"><i class="fas fa-check"></i> Aplicar</button> 
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6" id="divClientes">
        <table id="tablaClientes" class="table">
          <thead>
            <tr> 
              <th>Folio</th>
              <th>Cliente</th>
              <th>Recepción</th>
              <th>Creación</th>
            </tr>
          </thead>
        </table>
      </div>
      <div class="col-md-6" id="divPuntos">
        <table id="tablaPuntos" class="table">
          <thead>
            <tr>
              <th>Punto Muestreo</th>
              <th>Norma</th>
              <th>Parametros</th>
              <th>Observacion</th>
              <th>pH <2 </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  
  </div>

  @section('css')
  <link rel="stylesheet" href="{{ asset('public/css/laboratorio/metales/observacion.css')}}">
  @endsection

  @section('javascript')
  <script src="{{asset('public/js/laboratorio/metales/observacion.js')}}?v=1.0.3"></script>
  @stop

@endsection