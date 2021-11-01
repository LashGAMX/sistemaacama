@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="far fa-eye"></i>
    Asignación lote
  </h6>
 
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="row">
              
            <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleFormControSelect1">Tipo fórmula</label>
                    <select class="form-control">                        
                        @foreach ($tipoFormula as $formula)
                          <option value={{$formula->Id_tipo_formula}}>{{$formula->Tipo_formula}}</option>
                        @endforeach
                      </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                    <label for="exampleFormControSelect1">Método</label>
                      <select class="form-control">
                          <option value="0">Sin seleccionar</option>
                          <option value="1">Alimentos</option>
                          <option value="2">Flama</option>
                          <option value="3">Hidruros</option>
                          <option value="4">Horno</option>
                        </select>
                  </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Fecha recepción</label>
                    <input type="date" class="form-control" placeholder="Fecha lote">
                </div>
              </div>
              <div class="col-md-2">
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
                    <button class="btn btn-success"  data-toggle="modal" data-target="#exampleModal">Asignar</button>
                </div>
            </div>
            <table class="table" id="tableAsignar">  
                <thead>
                  <tr>
                    <th scope="col">ID Solicitud</th>
                    <th scope="col">Núm. Muestra</th>
                    <th scope="col">Fecha recepción</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Punto de muestreo</th>
                    <th scope="col">Norma</th>
                    <th scope="col">Fórmula</th>                    
                    <th scope="col">ID Lote</th>
                    <th scope="col">Fecha Lote</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>                    
                  </tr>
                  
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>                    
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Asignar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h2>¿Estas seguro que deseas asignar el lote?</h2>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          <button type="button" class="btn btn-primary">Si</button>
        </div>
      </div>
    </div>
  </div>


  @stop

  @section('css')
  <link rel="stylesheet" href="{{ asset('css/laboratorio/observacion.css')}}">
  @endsection

  @section('javascript')
  <script src="{{asset('js/laboratorio/asignar.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection