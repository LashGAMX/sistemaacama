@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fas fa-chart-area"></i>
    Curva de calibración
  </h6>

  
  <div class="container-fluid"> 
    <div class="row">
      <div class="col-md-4">
        <button class="btn btn-success" data-toggle="modal" data-target="#modalCrear"><i class="voyager-plus"></i> Crear</button>
        
      </div>
      <div class="col-md-4">
        <div class="col-md-4">
          <div class="form-group">
            <label for="exampleFormControSelect1">Lote</label>
              <select class="form-control">
                  <option value="0">Sin seleccionar</option>
                  <option value="1">Acreditados</option>
                  <option value="2">Metales alimentos</option>
                  <option value="3">Metales pesados en biosolidos</option>
                  <option value="4">Metales potable</option>
                  <option value="5">Metales purificadora</option>
                  <option value="6">Metales residual</option>
                  <option value="7">Miliequivalentes</option>
                  <option value="8">No acreditados</option>
                </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="exampleFormControSelect1">Fórmula</label>
              <select class="form-control">
                <option>Sin selecionar</option>
                @foreach ($formula as $item)
                <option value="{{$item->Id_formula}}">{{$item->Formula}}</option>
            @endforeach
                </select>
          </div>
        </div>
  
      </div>
      <div class="col-md-4">
        <input type="search" class="form-control" placeholder="Buscar">
      </div>
  
      <div class="col-md-12">
        <table class="table table-hover table-striped">
          <thead class="thead-dark">
                  <tr>
                   
                      <th>Lote</th>
                      <th>Formula</th>
                      <th>Técnica</th>
                      <th>STD</th>
                      <th>Concentración</th>
                      <th>ABS1</th>
                      <th>ABS2</th>
                      <th>ABS3</th>
                      <th>Promedio</th>
                    </tr>
              </thead>
              <tbody>
                @foreach ($model as $item) 
                <td>{{$item->Id_lote}}</td>
                <td>{{$item->Id_formula}}</td>
                <td>{{$item->Id_tecnica}}</td>
                <td>{{$item->STD}}</td>
                <td>{{$item->Concentracion}}</td>
                <td>{{$item->ABS1}}</td>
                <td>{{$item->ABS2}}</td>
                <td>{{$item->ABS3}}</td>
                <td>{{$item->Promedio}}</td>
                <td>
                  <button type="button" class="btn btn-warning">
                  <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
                  <button type="button" class="btn btn-primary"><i class="voyager-external"></i> <span hidden-sm hidden-xs>ver</span> </button>
                </td>
              </tr>
              @endforeach
              </tbody>
          </table>
      </div>
    </div>
        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
               <form wire:submit.prevent="create">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Nueva constante</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body"> 
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">STD</label>
                        <select class="form-control">
                          <option value="0">Blanco</option>
                          <option value="1">STD1</option>
                          <option value="2">STD2</option>
                          <option value="3">STD3</option>
                          <option value="4">STD4</option>
                          <option value="5">STD5</option>
                          <option value="6">STD6</option>
                          <option value="7">STD7</option>
                          <option value="8">STD8</option>
                        </select>
                    </div>
                        <div class="col-md-6">
                            <label for="">Concentración</label>
                            <input type="text" id='concentracion' class="form-control" placeholder="Concentración">
                        </div>
                        <div class="col-md-6">
                            <label for="">ABS1</label>
                            <input type="text" id='ABS1' class="form-control" placeholder="ABS1">
                        </div>
                        <div class="col-md-6">
                          <label for="">ABS2</label>
                          <input type="text" id='ABS1' class="form-control" placeholder="ABS1">
                      </div>
                      <div class="col-md-6">
                        <label for="">ABS3</label>
                        <input type="text" id='ABS1' class="form-control" placeholder="ABS1">
                    </div>
                    <div class="col-md-6">
                      <label for="">Promedio</label>
                      <input type="text" id='promedio' class="form-control" placeholder="Promedio">
                  </div>
                  <div class="col-md-6">
                    <button type="button" id="calcular" class="btn btn-success">Calcular</button>
                </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary">Guardar</button>
                </div>
              </form>
              </div>
            </div>
            
           </div>


    </div> 
  @stop
@endsection  

@section('javascript')
<script src="{{asset('js/laboratorio/curva.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop