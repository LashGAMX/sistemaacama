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
        <button type="button"class="btn btn-success"  data-toggle="modal" data-target="#modalCrear"id="btnCrear"><i class="voyager-plus"></i> Crear</button>
        {{-- <button type="button" class="btn btn-warning" id="editar" data-toggle="modal" data-target="#modalCrear"> <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
--}}
      </div> 

        <div class="col-md-2">
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
        <label for="">b</label>
        <input  type="text" id='b' class="form-control" placeholder="b" disabled>
    </div>
    <div class="col-md-3">
      <label for="">m</label>
      <input type="text" id='m' class="form-control" placeholder="m" disabled>
  </div>
  <div class="col-md-3">
    <label for="">r</label>
    <input type="text" id='r' class="form-control" placeholder="r" disabled>
</div>
<div class="col-md-3">
  <button class="btn btn-success" id="setConstantes" ><i class="far fa-save"></i> Guardar</button> 
</div>
    </div>
        


    </div> 


     <!-- Modal -->
  <div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title" id="exampleModalLabel">Crear Curva</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <select class="form-control" id="idAreaModal">
                <option>Selecciona area</option>
                @foreach ($area as $item)
                <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
              @endforeach
                </select>
            </div>
           
            <div class="col-md-6" id="divParametroModal">
              <select class="form-control" id="idParametroModal">
                <option>Selecciona parametro</option>
                {{-- @foreach ($parametro as $item)
                <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                @endforeach --}}
                </select>
            </div>

            <div class="col-md-6">
              <label>Fecha Inicio</label> 
              <input type="date" id="fechaInicio">
              
            </div> 
            <div class="col-md-6">
              <label>Fecha fin</label> 
              <input type="date" id="fechaFin">
              
            </div> 
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="CreateStd">Crear</button>
        </div>
      </div>
    </div>
  </div>

  @stop
@endsection  

@section('javascript')
    <script src="{{asset('/public/js/laboratorio/curva.js')}}"></script>
    <script src="{{ asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{ asset('/public/js/libs/tablas.js') }}"></script>
@stop