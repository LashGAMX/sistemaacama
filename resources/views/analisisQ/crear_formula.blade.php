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
            <button type="button" class="btn btn-success" id="btnGuardar" >Guardar</button>
          </div>
          <div class="col-md-4" id="divProbar">
            <button type="button" id="btnProbar" data-toggle="modal" data-target="#modalProbar" class="btn btn-info">Probar</button>
          </div>
        </div>
      </div>
      <input hidden id='idFormula' type="text" value="{{@$formulas->Id_formula}}">
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="">Parámetro</label>
              <select class="form-control" id="parametro">
                  @foreach ($parametro as $item)
                @if (@$formulas->Id_parametro==$item->Id_parametro)
                <option selected value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                @else
                <option value="{{$item->Id_parametro}}">{{$item->Parametro}}</option>
                @endif
                      
                  @endforeach
                </select>
            </div>
          </div>
          <div class="col-md-12">
          <div class="form-group">
            <label for="">Área</label>
            <select class="form-control" id="area">
                @foreach ($area as $item)
                @if (@$formulas->Id_area==$item->Id_area_analisis)
                <option selected value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
                @else
                <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
                @endif
          
                @endforeach
              </select>
          </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
              <label for="">Técnica</label>
              <select class="form-control" id="tecnica">
                @foreach ($tecnica as $item)
                @if (@$formulas->Id_tecnica==$item->Id_tecnica)
                <option selected value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
                @else
                <option value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
                @endif
                
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
      
                  <input value="{{@$formulas->Formula}}" type="text" class="form-control" id="formula" name="formula" placeholder="Formula">
                </div>
              </div>
              <div class="col-md-12"> 
                <div class="form-group">
                  <label for="">Formula sistema</label>
                  <input value="{{@$formulas->Formula_sistema}}" type="text" class="form-control" id="formulaSis" name="formula_sistema" placeholder="Formula sistema">  
                  <button type="button" id="btnAsignar" onclick="tablaVariables()" class="btn btn-danger">Asignar</button>
                </div>
              </div>
              <div class="col-md-8"> 
              <div class="form-group" id=divDecimales>
                <label for="">Decimales</label>
                <input type="text" class="form-control" id="decimales" name="decimales" placeholder="Decimales a considerar">  
              </div>
              <div class="col-md-12">
                <div id="tablaVariables">
                </div>
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
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><strong>Reglas formula sistema</strong></h5>
                    @foreach ($reglas as $item)
                    <p><strong>{{$item->Descripcion}}</strong>: {{$item->Regla}}</p>
                    @endforeach
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal fade" id="modalProbar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Probar formula</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <input type="text" class="form-control" id="formulaGen" placeholder="Formula a calcular" disabled>
          </div>
          <div class="col-md-12">
            <div id="inputVar">

            </div>
          </div>
          <div class="col-md-12">
            <button class="btn btn-success" id="btnCalcular">Calcular</button>
          </div>
          <div class="col-md-6">
            <input type="text" class="form-control" id="resultadoCal" placeholder="Resultado" disabled>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="unidad" placeholder="Unidad" disabled>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      
      </div>
    </div>
  </div>
</div>

@endsection  
 
@section('javascript')
<script src="{{asset('js/analisisQ/crear_formula.js')}}"></script>
<script src="{{asset('js/libs/componentes.js')}}"></script>
<script src="{{asset('js/libs/tablas.js')}}"></script>
@stop
