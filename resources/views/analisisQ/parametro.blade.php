@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <button class="btn btn-success" data-toggle="modal" data-target="#modalParametro"><i class="fas fa-plus"></i> Crear</button>
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

<div class="modal fade" id="modalParametro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar parámetro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-3">
                <input type="text" id="idParametro" hidden>
                {{-- <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="status">
                  <label class="custom-control-label" for="customSwitch1">Status</label>
                </div>  --}}
                  <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="curva">
                      <label class="custom-control-label" for="customSwitch1">Curva</label>
                  </div>       
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Laboratorio</label>
                          <select class="form-control" id="sucursal">
                          @foreach ($laboratorios as $item)
                              <option value="{{$item->Id_sucursal}}">{{$item->Sucursal}}</option>
                          @endforeach
                        </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="parametro">Nombre parámetro</label>
                     <input type="text" class="form-control" placeholder="Parámetro" id="parametro">
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Unidad</label>
                      <select class="form-control" id="unidad">
                      @foreach ($unidades as $item)
                          <option value="{{$item->Id_unidad}}">{{$item->Unidad}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Tipo fórmula</label>
                      <select class="form-control" id="tipo">
                      @foreach ($tipos as $item)
                          <option value="{{$item->Id_tipo_formula}}">{{$item->Tipo_formula}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group"> 
                      <label for="">Area</label>
                      <select class="form-control" id="area">
                      @foreach ($areas as $item)
                          <option value="{{$item->Id_area_analisis}}">{{$item->Area_analisis}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="">Norma</label>
                      <select class="form-control" id="norma" size="5"  multiple >
                      @foreach ($normas as $item)
                        <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="limite">Límite Cuantificación</label>
                     <input type="text" class="form-control" placeholder="Limite" id="limite">
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Matriz</label>
                      <select class="form-control" id="matriz">
                      @foreach ($matrices as $item)
                          <option value="{{$item->Id_matriz_parametro}}">{{$item->Matriz}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Rama</label>
                      <select class="form-control" id="rama">
                      @foreach ($ramas as $item)
                          <option value="{{$item->Id_rama}}">{{$item->Rama}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Método</label>
                      <select class="form-control" id="metodo">
                      @foreach ($metodos as $item)
                          <option value="{{$item->Id_metodo}}">{{$item->Clave_metodo}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Tecnica</label>
                      <select class="form-control" id="tecnica">
                      @foreach ($tecnicas as $item)
                          <option value="{{$item->Id_tecnica}}">{{$item->Tecnica}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Procedimiento análisis</label>
                      <select class="form-control" id="procedimiento">
                      @foreach ($procedimientos as $item)
                          <option value="{{$item->Id_procedimiento}}">{{$item->Procedimiento}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="">Simbología Oficial</label>
                      <select class="form-control" id="simbologia">
                      @foreach ($simbologias as $item)
                          <option value="{{$item->Id_simbologia}}">{{$item->Simbologia}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              <div class="col-md-3"> 
                  <div class="form-group">
                      <label for="">Simbología Informes</label>
                      <select class="form-control" id="simbologiaInf">
                      @foreach ($simbologiasInf as $item)
                          <option value="{{$item->Id_simbologia_info}}">{{$item->Simbologia}}</option>
                      @endforeach
                    </select> 
                    </div>
              </div>
              <div class="col-md-3"> 
                <div class="form-group">
                    <label for="">Curva Padre</label>
                    <select class="form-control" id="CurvaPadre">
                      <option value="0">Sin seleccionar</option>
                    @foreach ($parametroPadre as $item)
                        <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}}</option>
                    @endforeach
                  </select> 
                  </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar cambios</button>
      </div>
    </form>
    </div>
  </div>
</div>


  @stop
  @section('javascript')
  <script src="{{asset('/public/js/analisisQ/parametro.js')}}?v=0.2.1"></script>
@stop  
@endsection
 