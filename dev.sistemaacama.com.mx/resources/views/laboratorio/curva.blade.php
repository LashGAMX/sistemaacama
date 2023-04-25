@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fas fa-chart-area"></i>
    Curva de calibración<input id="idUser" value="{{@$idUser}}" hidden>
  </h6>
  <div class="row">
    <div class="col-md-6">
      <div id="divTablaVigencias" style="width: 100%; overflow:scroll">
        <table class="table" id="tablaVigencias">
          <thead>
            <tr>
              <th>ID Parametro</th>
              <th>Fecha inicio</th>
              <th>Fecha final</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <br>
  <h4 style="margin-left: 15px">Limites de vigencia</h4>
  <div class="container-fluid"> 
    <div class="row">
      <div class="col-md-3">
        <label>Fecha Inicio</label> 
        <input type="date" id="fechaInicio">
      </div> 
      <div class="col-md-3">
        <label>Fecha fin</label> 
        <input type="date" id="fechaFin">
      </div> 
    </div>
    <hr>
    <div class="row">

        <div class="col-md-2" >
             <select class="form-control" id="idArea">
              <option value="">Selecciona Area</option>
              @foreach ($area as $item)
                <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
              @endforeach 
            </select>
      </div>

      <div class="col-md-2">
        <input type="date" class="form-control" id="fecha" >
        
      </div>
        <div class="col-md-2" id="divParametro">
          <select class="form-control" id="parametro">
            <option value="">Selecciona Parametro</option>
           
          </select>
        </div>
      <div class="col-md-1">
        <button class="btn btn-info" id="buscar"><i class="voyager-serch"></i> Buscar</button>
      </div>
      <div class="col-md-2">
        <button type="button"class="btn btn-success" id="CreateStd"><i class="voyager-plus"></i> Crear</button>
      </div> 
      
      

      <div class="col-md-12">
        <div id="divTablaStd">
          <table class="table" id="tableStd">
            <thead class="">
                    <tr>
                      <th>Id</th>
                        <th>STD</th>
                        <th>Concentración</th>
                        <th>ABS1</th>
                        <th>ABS2</th>
                        <th>ABS3</th>
                        <th>Promedio</th>
                      </tr>
                </thead>
                </tbody>
            </table>
        </div>
     
      </div>
      <div class="col-md-3">
        <button class="btn btn-danger" id="formula" ><i class="fas fa-calculator"></i> Calcular</button>
      </div>
      <div class="col-md-3">
        <label for="">b</label>
        <input  type="number" id='b' class="form-control" placeholder="b" >
    </div>
    <div class="col-md-3">
      <label for="">m</label>
      <input type="number" id='m' class="form-control" placeholder="m" >
  </div>
  <div class="col-md-3">
    <label for="">r</label>
    <input type="number" id='r' class="form-control" placeholder="r" >
</div>
<div class="col-md-3">
  <button class="btn btn-success" id="setConstantes" ><i class="far fa-save"></i> Guardar</button> 
</div>
  


      <div class="col-md-6">
        <label>Vigencia: </label>
        <label id="vigencia"> </label>
      </div>


  <div class="row">
    <div class="col-md-6">
      <h5>PARAMETROS HIJOS</h5>
      <button class="btn btn-danger" id="replicar">replicar</button>
      <div id="divTablaHijos">
      <table class="table" id="tablaHijos">
        <thead>
          <tr>
            <th>Id</th>
            <th>Parametro</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
      
    </div>
  </div>
</div>

   

  @stop
@endsection  

@section('javascript')
    <script src="{{asset('/public/js/laboratorio/curva.js')}}?v=1.2.0"></script>
    <script src="{{ asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{ asset('/public/js/libs/tablas.js') }}"></script>
@stop