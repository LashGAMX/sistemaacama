@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Análisis (Solo visualización)
  </h6>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Filtro</label>
                <select class="form-control">
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
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Fecha Recepción</th>
                    <th>Punto muestreo</th>
                    <th>Norma</th>
                    <th style="width: 30%">Parametros</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>127-5/1</td>
                    <td>Cliente</td>
                    <td>22/09/2021</td>
                    <td>Punto final</td>
                    <td>NOM-SEMARNAT-001</td>
                    <td>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Accusamus temporibus rerum dignissimos atque odio maiores vitae ea quasi, deserunt molestiae id velit, animi necessitatibus. Voluptas non corporis voluptatum facilis aut ipsam, omnis dolorum sit est illum nemo natus iusto tempore maiores incidunt blanditiis eligendi consectetur iure praesentium. Ipsa, ipsum error.</td>
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


