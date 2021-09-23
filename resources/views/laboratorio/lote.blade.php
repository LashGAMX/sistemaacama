@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="far fa-eye"></i>
    Lote
  </h6>
 
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="row">
              
            <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleFormControSelect1">Tipo formula</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">Asignado</option>
                        <option value="2">Sin asignar</option>
                      </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Fecha lote</label>
                    <input type="date" class="form-control" placeholder="Fecha lote">
                </div>
              </div>


              <div class="col-md-2">
                <button class="btn btn-success">Buscar</button>
              </div>

          </div>
      </div>
    

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-success">Crear lote</button>
                </div>
            </div>
            <table class="table" id="tableObservacion"> 
                <thead>
                  <tr>
                    <th scope="col">Cerrado</th>
                    <th scope="col">RcpLoteAnalisis</th>
                    <th scope="col">Formula</th>
                    <th scope="col">TipoFormulaAnalisis</th>
                    <th scope="col">FechaLote</th>
                    <th scope="col">FechaHora</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
        </div>
      </div>
</div>
  @stop

  @section('css')
  <link rel="stylesheet" href="{{ asset('css/laboratorio/observacion.css')}}">
  @endsection

  @section('javascript')
  <script src="{{asset('js/laboratorio/observacion.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection