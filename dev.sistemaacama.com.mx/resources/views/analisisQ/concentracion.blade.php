@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-layer-group"></i>
    Concentracion
  </h6>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
             @foreach ($norma as $item)
                 <button class="btn btn-success" onclick="getParametroNorma({{$item->Id_norma}});">{{$item->Clave_norma}}</button>
             @endforeach
        </div>
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
              <div id="tableParametros">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Parametro</th>
                      <th>T. Formula</th>
                      <th>Opc</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="col-md-6">
              <div id="tableStd">
                <table class="table">
                  <h6>Parametro: <input type="text" value="" disabled>&nbsp;<button class="btn btn-success">Guardar</button>&nbsp;&nbsp;<button class="btn btn-success"><i class="voyager-plus"></i></button></h6>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Concentracion</th>
                      <th>Opc</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
  @stop

  <script src="{{asset('/public/js/analisisQ/concentracion.js')}}"></script>

@endsection  