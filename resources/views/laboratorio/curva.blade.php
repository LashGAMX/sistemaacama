@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fas fa-chart-area"></i>
    Curva de calibración <input id="idVal" value="{{@$id}}" hidden>
  </h6>

  
  <div class="container-fluid"> 
    <div class="row">
      <div class="col-md-3">
        <button class="btn btn-success" id="CreateStd"><i class="voyager-plus"></i> Crear</button>
        <button type="button" class="btn btn-warning" id="editar" data-toggle="modal" data-target="#modalCrear">
          <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>

      </div> 

        <div class="col-md-2">
             <select class="form-control" id="area">
              <option value="">Selecciona Area</option>
              @foreach ($area as $item)
                <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
              @endforeach
            </select>
      </div>

      <div class="col-md-2">
        <select class="form-control" id="idLote">
          <option value="">Selecciona Lote</option>
          @foreach ($lote as $item)
          @if (@$id==$item->Id_lote)
          <option selected value="{{$item->Id_lote}}">{{$item->Fecha}} / Id:{{$item->Id_lote}}</option>
          @else
          <option value="{{$item->Id_lote}}">{{$item->Fecha}} Id:{{$item->Id_lote}}</option>
          @endif
          @endforeach
          </select>
</div>
        <div class="col-md-2" id="divParametro">
          <select class="form-control" id="parametro">
            <option value="">Selecciona Parametro</option>
          </select>
        </div>
      <div class="col-md-1">
        <button class="btn btn-info" id="buscar"><i class="voyager-serch"></i> Buscar</button>
      </div>
      
      
  
      <div class="col-md-12">
        <div id="divTablaStd">
          <table class="table" id="tableStd">
            <thead class="">
                    <tr>
                      <th>Id</th>
                        <th>Lote</th>
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
        <label for="">B</label>
        <input  type="text" id='b' class="form-control" placeholder="B">
    </div>
    <div class="col-md-3">
      <label for="">M</label>
      <input type="text" id='m' class="form-control" placeholder="M">
  </div>
  <div class="col-md-3">
    <label for="">R</label>
    <input type="text" id='r' class="form-control" placeholder="R">
</div>
<div class="col-md-3">
  <button class="btn btn-success" id="setConstantes" ><i class="far fa-save"></i> Guardar</button> 
</div>
    </div>
        


    </div> 
  @stop
@endsection  

@section('javascript')
    <script src="{{asset('js/laboratorio/curva.js')}}"></script>
    <script src="{{ asset('js/libs/componentes.js')}}"></script>
    <script src="{{ asset('js/libs/tablas.js') }}"></script>
@stop