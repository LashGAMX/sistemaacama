@extends('voyager::master')

@section('content')

<div class="container-fluid">

  <div class="row">
        
      <div class="col-md-12">
          <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <label for="laboratorio">Laboratorio</label>
                    <select class="form-control" id="lab"> 
                        @foreach ($lab as $item)
                            <option value="{{$item->Id_sucursal}}">{{$item->Sucursal}}</option>
                        @endforeach
                    </select> 
                  </div>
                  <div class="col-md-5">
                    <label for="norma">Normas</label>
                    <select class="form-control" id="norma">  
                        <option value="0">Sin seleccionar</option>
                        @foreach ($norma as $item)
                            <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-dark btn-sm" onclick="confirmar()" ><i class="voyager-list-add"></i> Anual</button>
                  </div>
                </div>
              </div>
            </div>
      </div>
      
      <div class="col-md-12"> 
        <div class="card">
          <div class="card-body" id="divPrecios">
              <table id="precios" class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Sucursal</th>
                    <th># Parametro</th>
                    <th>Parametro</th>
                    <th>Formula</th>
                    <th>Norma</th>
                    <th>Precio</th>
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


<div  class="modal fade" id="modalPrecioAnual"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form wire:submit.prevent="createPrecio">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Incrementar precio anual</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-12">
                      <label>Precio anual %</label>
                      <input type="number" class="form-control" id="porcentaje" placeholder="Precio %" required>
              </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" id="btnSetPrecioAnual" class="btn btn-primary">Guardar cambios</button>
      </div>
    </form>
    </div>
  </div>
</div>


@section('javascript')
  <script src="{{asset('/public/js/precios/catalogo.js')}}?v=0.0.1"></script>
@stop
@endsection 

