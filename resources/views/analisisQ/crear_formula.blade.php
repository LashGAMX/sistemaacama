@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="fa fa-square-root-alt"></i>
    Formulas
  </h6>

  @stop
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <button type="button" class="btn btn-success">Guardar</button>
          </div>
          <div class="col-md-4" id="divProbar">
            <button type="button" class="btn btn-info">Probar</button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Parámetro</label>
              <select class="form-control">
                  @foreach ($parametro as $item)
                      <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                  @endforeach
                </select>
            </div>
          </div>
          <div class="col-md-12">
          <div class="form-group">
            <label for="">Área</label>
            <select class="form-control">
                @foreach ($area as $item)
                    <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
                @endforeach
              </select>
          </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
              <label for="">Técnica</label>
              <select class="form-control">
                @foreach ($tecnica as $item)
                <option value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
                @endforeach
                </select>
            </div>
        </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Formula</label>
                  <input type="text" class="form-control" id="formula" placeholder="Formula">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Formula sistema</label>
                  <input type="text" class="form-control" id="formulaSis" placeholder="Formula sistema">  
                  <button type="button" id="btnAsignar" onclick="tablaVariables()" class="btn btn-danger">Asignar</button>
                </div>
              </div>
              <div class="col-md-12">
                <div id="tablaVariables">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
              <div class="form-group">
                <textarea style="width: 100%; height: 200px;" disabled>
                  Reglas formula sistema
                  ---------------------------------------------
                  Constantes: const
                  Variables: var
                  Formula Nivel 1 (Global): fg
                  Formula Nivel 2: Aux
                  Formula Nivel 3: Aux2
                </textarea>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection  
 
@section('javascript')
<script src="{{asset('js/analisisQ/crear_formula.js')}}"></script>
@stop
