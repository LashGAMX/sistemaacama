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
          <div class="row">
              
            <div class="col-md-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Tipo formula</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">Asignado</option>
                        <option value="2">Sin asignar</option>
                      </select>
                </div>
              </div>

              <div class="col-md-1">
                <div class="form-group">
                  <label for="exampleFormControlSelect1">PH < 2</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                      </select>
                </div>
              </div>

              <div class="col-md-1">
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Sólidos</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                      </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Olor</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                      </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="exampleFormControlSelect1">Color</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">SI</option>
                        <option value="2">NO</option>
                      </select>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label for="exampleFormControlTextarea1">Descripción</label>
                  <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
              </div>

              <div class="col-md-2">
                <button class="btn btn-success">Aplicar</button>
              </div>

          </div>
      </div>
    

        <div class="col-md-12">
            <table class="table" id="tableObservacion"> 
                <thead>
                  <tr>
                    <th scope="col">Folio servicio</th>
                    <th scope="col">Nombre cliente</th>
                    <th scope="col">Fecha recepción</th>
                    <th scope="col">Punto muestreo</th>
                    <th scope="col">Norma</th>
                    <th scope="col">Parámetro</th>
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