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
        <button type="submit" class="btn btn-success">Guardar</button>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
          <div class="form-group">
            <label for="">Área</label>
            <select class="form-control">
                <option value="0">Selecciona...</option>
                <option value="1">ABS</option>
                <option value="2">POTABLE</option>
                <option value="2">FISICOQUIMICO</option>
              </select>
          </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
              <label for="">Técnica</label>
              <select class="form-control">
                  <option value="0">Selecciona...</option>
                  <option value="1">ABS</option>
                  <option value="2">POTABLE</option>
                  <option value="2">FISICOQUIMICO</option>
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
                  <input type="text" wire:model='formula' class="form-control" placeholder="Formula">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label for="">Formula sistema</label>
                  <input type="text" wire:model='Prom_Dmin' class="form-control" placeholder="Formula sistema">  
                  <button type="submit" class="btn btn-danger">Asignar</button>
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
                <textarea style="width: 100%; height: 250px;" disabled>
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

<script src="{{asset('js/analisisQ/craer_formula.js')}}"></script>
@stop
