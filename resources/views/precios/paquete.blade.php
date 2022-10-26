@extends('voyager::master')

@section('content')

  @section('page_header')
<div class="container-fluid">

    <div class="row">

      <div class="col-md-12">
        <div class="card">
            <div class="card-body">
              <div class="row">
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
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPaquete"><i class="fa fa-plus"></i> Crear</button>
                </div>
              </div>
            </div>
          </div>
    </div>
        
        <div class="col-md-12"> 
          <div class="card">
            <div class="card-body" id="divPaquete">
                <table id="tabPaquete" class="table table-sm" border="1">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th rowspan="1"></th>
                      <th colspan="6"><center>Precios</center></th>
                    </tr>
                    <tr>
                      <th>Id</th>
                      <th>Paquete</th>
                      <th>Instantaneo</th>
                      <th>1 - 4 Hrs</th>
                      <th>4 - 8 Hrs</th>
                      <th>8 - 12 Hrs</th>
                      <th>12 - 18 Hrs</th>
                      <th>18 - 24 Hrs</th>
                    </tr>
                  </thead>
                </table>
            </div>
          </div>
        </div>

      </div>

      

</div> 

<!-- Modal -->
<div class="modal fade" id="modalPaquete" tabindex="-1" aria-labelledby="modalPaquete" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear precio paquete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="norma">Paquetes</label>
        <select class="form-control" id="paquete">  
            <option value="0">Sin seleccionar</option>
            @foreach ($paquete as $item)
                <option value="{{$item->Id_subnorma}}">{{$item->Clave}}</option>
            @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnCrear">Crear</button>
      </div>
    </div>
  </div>
</div>
@section('javascript')
  <script src="{{asset('/public/js/precios/paquete.js')}}?v=0.0.1"></script>
@stop
@endsection 


 