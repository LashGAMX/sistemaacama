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
                <label>Tipo fórmula</label>                

                <select class="form-control" id="tipoFormula">
                  @foreach($formulas as $formula)
                    <option value="{{$formula->Id_tipo_formula}}">{{$formula->Tipo_formula}}</option>
                  @endforeach
                </select>                    

              </div>                
            </div>                          

            <div class="col-md-1">
              <button type="button" id="btnBuscar" class="btn btn-success">Buscar</button>
            </div>

            <div class="col-md-2">                                
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

            <div class="col-md-1">
              <div class="form-group">
                <label for="exampleFormControlSelect1">Olor</label>
                <select class="form-control">
                  <option value="0">Sin seleccionar</option>
                  <option value="1">SI</option>
                  <option value="2">NO</option>
                </select>
              </div>
            </div>

            <div class="col-md-1">
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
                <label for="exampleFormControlTextarea1">Observaciones</label>
                <textarea class="form-control" id="observacionesGenerales" rows="3"></textarea>
              </div>
            </div>

            <div class="col-md-2">
              <button class="btn btn-success">Aplicar</button>
            </div>

          </div>
      </div>
    
      <div class="col-md-12">

        <div id="contenedorGeneral">
            
          <div id="contenedorIzq">
            <table class="table tableObservacion" id="tablaObservacion"> 
              <thead>
                <tr>
                  <th>Folio servicio</th>
                  <th>Nombre cliente</th>
                  <th scope="col">Fecha recepción</th>
                  <th>FechaCreación</th>       
                  <th>Punto de muestreo</th>
                  <th>Norma</th>
                  <th>Parámetros</th>
                  <th>Observaciones</th>
                  <th>Es pH < 2</th>
                  <th>Sólidos</th>           
                </tr>
              </thead>
              <tbody>
                             
              </tbody>
            </table>
          </div>            
          
        </div>
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