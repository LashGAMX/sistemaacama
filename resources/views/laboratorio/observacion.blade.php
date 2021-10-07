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
              
            <div class="col-md-1">
              <div class="form-group">
                <label>Tipo fórmula</label>                

                <select class="form-control">
                  @foreach($formulas as $formula)
                    <option value="{{$formula->Id_tipo_formula}}">{{$formula->Tipo_formula}}</option>
                  @endforeach
                </select>                    

              </div>                
            </div>                          

            <div class="col-md-1">
              <button type="button" class="btn btn-success">Buscar</button>
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
            <table class="table tableObservacion" id="primeraTabla"> 
              <thead>
                <tr>
                  <th scope="col">Folio servicio</th>
                  <th scope="col">Nombre cliente</th>
                  <th scope="col">Fecha recepción</th>
                  <th scope="col">FechaCreación</th>                  
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>hola</td>
                  <td>hola</td>
                  <td>hola</td>
                  <td>hola</td>
                </tr>
                <tr>
                  <td>hola</td>
                  <td>hola</td>
                  <td>hola</td>
                  <td>hola</td>                  
                </tr>                
              </tbody>
            </table>
          </div>            

          <div id="contenedorDer">
            <table class="table tableObservacion" id="segundaTabla"> 
              <thead>
                <tr>
                  <th scope="col">Punto de muestreo</th>
                  <th scope="col">Norma</th>
                  <th scope="col">Parámetros</th>
                  <th scope="col">Observaciones</th>
                  <th scope="col">Es pH < 2</th>
                  <th scope="col">Sólidos</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>hola</td>
                  <td>hola</td>
                  <td>hola</td>
                  <td contenteditable="true"><textarea class="form-control" id="observacionIndividual" rows="2"></textarea></td>
                  <td>
                    <select class="form-control">
                      <option value="0">Sin seleccionar</option>
                      <option value="1">SI</option>
                      <option value="2">NO</option>                    
                    </select>
                  </td>
                  <td>
                    <select class="form-control">
                      <option value="0">Sin seleccionar</option>
                      <option value="1">SI</option>
                      <option value="2">NO</option>                    
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>hola</td>
                  <td>hola</td>
                  <td>hola</td>
                  <td contenteditable="true"><textarea class="form-control" id="observacionIndividual" rows="2"></textarea></td>
                  <td>
                    <select class="form-control">
                      <option value="0">Sin seleccionar</option>
                      <option value="1">SI</option>
                      <option value="2">NO</option>                    
                    </select>
                  </td>
                  <td>
                    <select class="form-control">
                      <option value="0">Sin seleccionar</option>
                      <option value="1">SI</option>
                      <option value="2">NO</option>                    
                    </select>
                  </td>
                </tr>                
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