@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <button></button>
      </div>
        <div class="col-md-12" id="divTabla">
            <table class="table">
              <tr>
                <th>Id</th>
                <th>Sucursal</th>
                <th>Rama</th>
                <th>Parámetro</th>
                <th>Unidad</th>
                <th>Método prueba</th>
                <th>C. Metodo</th>
                <th>Norma</th>
                <th>Limite</th>
                <th>Opc</th>
              </tr>
            </table>
        </div>
      </div>
</div>
  @stop
  @section('javascript')
  <script src="{{asset('/public/js/analisisQ/parametro.js')}}?v=0.0.1"></script>
@stop  
@endsection
 