@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fab fa-cuttlefish"></i>
    Constantes
  </h6>
  @stop

 <div class="container-fluid"> 
  <div class="row">
    <div class="col-md-4">
        <a type="button" id="btnCrear" data-toggle="modal" data-target="#modalCrear" class="btn btn-success"> <i class="voyager-plus"></i> Crear</a>
      {{-- <a href="{{url('/admin/analisisQ/formulas/nivel')}}" class="btn btn-primary"><i class="fa fa-level-up-alt"></i> Nivel formula</a> --}}
      {{-- <a href="{{url('/admin/analisisQ/formulas/nivel')}}" class="btn btn-warning"><i class="fab fa-cuttlefish"></i> Constante</a> --}}
    </div>
    <div class="col-md-4">

    </div>
    <div class="col-md-4">
      <input type="search" class="form-control" placeholder="Buscar">
    </div>

    <div class="col-md-12">
      <table class="table table-hover table-striped">
        <thead class="thead-dark">
                <tr>
                 
                    <th>Contante</th>
                    <th>Valor</th>
                    <th>Descipción</th>
                    {{-- <th>Formula</th>
                    <th>Formula Sistema</th>
                    <th>Resultado</th> --}}
                  </tr>
            </thead>
            <tbody>
              @foreach ($constantes as $item) 
              <td>{{$item->Constante}}</td>
              <td>{{$item->Valor}}</td>
              <td>{{$item->Descripcion}}</td>
              {{-- <td>{{$item->Formula}}</td>
              <td>{{$item->Formula_sistema}}</td>
              <td>{{$item->Resultado}}</td> --}}
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
  </div> 

  <!-- Modal -->
<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form wire:submit.prevent="store">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nueva constante</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"> 
              <div class="row">
                  <div class="col-md-6">
                      
                      <label for="">Constante</label>
                      <input type="text" id='constante' class="form-control" placeholder="constante">
                  </div>
                  <div class="col-md-6">
                      <label for="">Valor</label>
                      <input type="text" id='valor' class="form-control" placeholder="Valor">
                  </div>
                  <div class="col-md-6">
                      <label for="">Descripción</label>
                      <input type="text" id='descripcion' class="form-control" placeholder="Descripción">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" id="guardar" class="btn btn-primary">Guardar</button>
          </div>
        </form>
        </div>
      </div>
  </div>

@endsection  
@section('javascript')
<script src="{{asset('js/analisisQ/constantes.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop