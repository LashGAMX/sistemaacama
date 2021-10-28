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
        <button type="button" class="btn btn-warning" id="editar" data-toggle="modal" data-target="#modalCrear">
          <i class="voyager-edit"></i> <span hidden-sm hidden-xs>editar</span> </button>
      </div> <div class="col-md-1">
        <button class="btn btn-info" id="buscar"><i class="voyager-serch"></i> Buscar</button>
      </div>
  
      <div class="col-md-3">
              <select class="form-control">
                  <option value="0">Selecciona Lote</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                </select>
      </div>
      <div class="col-md-3">
        <input type="search" class="form-control" placeholder="Buscar">
      </div>
  
      <div class="col-md-12">
        <table class="table table-hover table-striped" id="tableStd">
          <thead class="thead-dark">
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
              <tbody>
              @if ($model == "")
                  
              @else
              @foreach (@$model as $item)
              <tr>
              <td>{{$item->Id_std}}</td> 
              <td>{{$item->Id_lote}}</td>
              <td>{{$item->STD}}</td> 
              <td>{{$item->Concentracion}}</td>
              <td>{{$item->ABS1}}</td>
              <td>{{$item->ABS2}}</td>
              <td>{{$item->ABS3}}</td>
              <td>{{$item->Promedio}}</td>
              <td></td>
          </tr>
          @endforeach 
              @endif
              </tbody>
          </table>
      </div>
      <div class="col-md-3">
        <button class="btn btn-danger" id="formula" ><i class="fas fa-calculator"></i> Calcular</button>
      </div>
      <div class="col-md-3">
        <label for="">B</label>
        <input type="text" id='b' class="form-control" placeholder="B">
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
  <button class="btn btn-success" id="create" ><i class="far fa-save"></i> Guardar</button>
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
                        <select class="form-control" id="std">
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
                          <input type="text" id='ABS2' class="form-control" placeholder="ABS1">
                      </div>
                      <div class="col-md-6">
                        <label for="">ABS3</label>
                        <input type="text" id='ABS3' class="form-control" placeholder="ABS1">
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
                  <button type="button" class="btn btn-primary" id="guardar">Guardar</button>
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