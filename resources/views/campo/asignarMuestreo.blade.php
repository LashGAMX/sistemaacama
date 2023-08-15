@extends('voyager::master')

@section('content')

  @section('page_header')
  
  @stop

  <div class="container-fluid">
    <div class="row">
      
    </div>
   <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-1">
          <button type="button" id="btnImprimir" class="btn btn-basic"><i class="fa fa-print"></i> Imprimir</button>
        </div>
        <div class="col-md-1">
          <button type="button" id="btnGenerar" class="btn btn-success"><i class="fa fa-plus"></i> Generar</button>
        </div>
        <div class="col-md-2">
          <button type="button" id="btnPlanMuestreo" class="btn btn-primary"><i class="fa fa-tools" ></i> Plan de muestreo</button>
        </div>
        <div class="col-md-2">
          <button type="button" id="btnObservacion" class="btn btn-primary" data-toggle="modal" data-target="#modalObs"><i class="fa fa-tools" ></i> Observacion</button>
        </div>
        <div class="col-md-6">
          <input type="month" class="form-control" id="month">
          <input type="date" class="form-control" id="daystart">
          <input type="date" class="form-control" id="dayfinish">
          <button type="button" class="btn btn-success"><i class="fa fa-search" id="btnBuscar""></i> Buscar</button>
        </div>
      </div>
    </div>
   </div>
  
    <div class="col-md-12" id="divListaServ">
      <table class="table table-sm" id="listaAsignar">
        <thead>
          <tr>
            <th>Id Solicitud</th>
            <th>Folio Servicio</th>
            <th>Nombre Cliente</th>
            <th>Servicio</th>
            <th>Tipo Descarga</th>
            <th>Fecha muestreo</th>
            <th>Observaciones</th>
            <th>Fecha Creación</th>
            <th>Fecha Actualización</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($model as $item) 
              @if ($item->Folio_servicio != NULL)
                <tr>
                  <td>{{$item->Id_solicitud}}</td>
                  <td>{{$item->Folio_servicio}}</td>
                  <td>{{$item->Nombres}}</td>
                  <td>{{$item->Servicio}}</td>
                  <td>{{$item->Descarga}}</td>
                  <td>{{$item->Fecha_muestreo}}</td>
                  <td>{{$item->Observacion}}</td>
                  <td>{{$item->created_at}}</td>
                  <td>{{$item->updated_at}}</td>
                </tr>
              @endif
          @endforeach
        </tbody>
      </table>
    </div>
    
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-2">
            <button type="button" id="btnAsignar" data-toggle="modal" data-target="#modalAsignar" class="btn btn-success "><i class="voyager-list-add"></i> Asignar</button>
          </div>
          <div class="col-md-2">
            <button type="button" id="btnAsignarMultiple" class="btn btn-success"  data-toggle="modal" data-target="#modalMultiple"><i class="voyager-list-add"></i> Asignar Multiple</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div id="divSolGenerada">
        <table class="table table-sm" id="solicitudGenerada">
          <thead>
            <tr>
              <th>Folio</th>
              <th>Punto de muestreo</th>
              <th>Captura</th>
              <th>Id muestreador</th>
              <th>Nombres</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="modalAsignar"aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h5 class="modal-title" id="exampleModalLabel">Asignar muestreador</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <select class="form-control select2" id="idUsuarios">
                <option>Selecciona un usuario</option>
                @foreach ($usuarios as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
                </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="btnGuardar">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal fade" id="modalObs" tabindex="-1" aria-labelledby="modalObs" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Observacion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Observación de muestreo</label>
          <textarea class="form-control" id="txtObervacion" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnGuardarObservacion" class="btn btn-primary">Guardar Observación</button>
      </div>
    </div>
  </div>
</div>

  <!-- Modal Asig. Multiple -->
  <div class="modal fade" id="modalMultiple" aria-labelledby="modalObs" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignacion multiple</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label for="idUser">Muestreador</label>
              <select class="form-control select2" id="idUser">
                <option>Selecciona un usuario</option>
                @foreach ($usuarios as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md-6">
              <label for="idPunto">Punto de muestreo</label>
              <select class="form-control" id="idPunto">
                <option value="0"> Sin seleccionar</option>
              </select>
            </div>
          </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnGuardarMuestreador" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

@endsection  


@section('javascript')
    <script src="{{asset('/public/js/campo/AsignarMuestreo.js')}}?v=1.0.3"></script>
    <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{asset('/public/js/libs/tablas.js')}}"></script>
   
@stop

