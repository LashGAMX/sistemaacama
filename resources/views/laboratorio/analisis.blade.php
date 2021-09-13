@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Análisis
  </h6>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <select class="form-select">
                    <option value="0">Sin seleccionar</option>
                    <option value="1">Asignado</option>
                    <option value="2">Sin asignar</option>
                  </select>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table" id="tableAnalisis"> 
                <thead>
                  <tr>
                    <th scope="col">Folio</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td scope="row">1</td>
                    <td>Mark</td>
                    <td>Otto</td>
                    
                  </tr>
                  <tr>
                    <td scope="row">2</td>
                    <td>Jacob</td>
                    <td>Thornton</td>
                  </tr>
                </tbody>
              </table>
        </div>
      </div>
</div>
  @stop

  @section('javascript')
  <script src="{{asset('js/laboratorio/analisis.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection  


