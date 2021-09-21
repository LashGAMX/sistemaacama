@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="far fa-eye"></i>
    Observación
  </h6>
 
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-12">
            <table class="table"> 
                <thead>
                  <tr>
                    <th scope="col" id="cabecera"><h4>Tipo fórmula</h4></th>                    
                    <th scope="col" id="cabecera"></th>
                    <th scope="col" id="cabecera"><h4>PH < 2</h4></th>
                    <th scope="col" id="cabecera"><h4>Sólidos</h4></th>
                    <th scope="col" id="cabecera"><h4>Olor</h4></th>
                    <th scope="col" id="cabecera"><h4>Color</h4></th>
                    <th scope="col" id="cabecera"><h4>Observación</h4></th>
                    <th scope="col" id="cabecera"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td id="cabecera">
                        <select class="form-select">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Asignado</option>
                            <option value="2">Sin asignar</option>
                        </select>
                    </td>
                    <td id="cabecera">
                        <button type="button" class="btn btn-success">Buscar</button>
                    </td>
                    <td id="cabecera">
                        <select class="form-select">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Asignado</option>
                            <option value="2">Sin asignar</option>
                        </select>
                    </td>
                    <td id="cabecera">
                        <select class="form-select">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Asignado</option>
                            <option value="2">Sin asignar</option>
                        </select>
                    </td>
                    <td id="cabecera">
                        <select class="form-select">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Asignado</option>
                            <option value="2">Sin asignar</option>
                        </select>
                    </td>
                    <td id="cabecera">
                        <select class="form-select">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Asignado</option>
                            <option value="2">Sin asignar</option>
                        </select>
                    </td>
                    <td id="cabecera">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Escribe la observación..." id="floatingTextarea"></textarea>                            
                        </div>
                    </td>
                    <td id="cabecera">
                        <button type="button" class="btn btn-success">Aplicar</button>
                    </td>
                  </tr>                  
                </tbody>
              </table>
        </div>

        <div class="col-md-12">
            <table class="table" id="tableObservacion"> 
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

  @section('css')
  <link rel="stylesheet" href="{{ asset('css/laboratorio/observacion.css')}}">
  @endsection

  @section('javascript')
  <script src="{{asset('js/laboratorio/observacion.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection