@extends('voyager::master')

@section('content')

  @section('page_header')
  
  @stop

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="col-md-2">
          <input type="Nombre" class="form-control" placeholder="nombre">
        </div>
        <div class="col-md-2">
          <input type="Folio" class="form-control" placeholder="folio">
        </div>
        <div class="col-md-2"> 
          <select type="Mes" class="form-control" placeholder="">
            <option>mes</option>
            <option>Enero</option>
            <option>Febrero</option>
            <option>Marzo</option>
            <option>Abril</option>
            <option>Mayo</option>
            <option>Junio</option>
            <option>Julio</option>
            <option>Agosto</option>
            <option>Septiembre</option>
            <option>Octubre</option>
            <option>Noviembre</option>
            <option>Diciembre</option>
        </select>
        </div>
        <div class="col-md-2">
          <input type="Año" class="form-control" placeholder="Año">
        </div> 
        <div class="col-md-2">
          <button type="button" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
        </div>
      </div>
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
        <div class="col-md-1">
          <button type="button" id="btnPlanMuestreo" class="btn btn-primary"><i class="fa fa-tools" ></i> Plan de muestreo</button>
        </div>
      </div>
    </div>
   </div>
  
    <div class="col-md-12">
      <table class="table table-sm" id="listaAsignar">
        <thead>
          <tr>
            <th>Id Solicitud</th>
            <th>Folio Servicio</th>
            <th>Nombre Cliente</th>
            <th>Ap. Paterno</th>
            <th>Ap. Materno</th>
            <th>Nombre</th>
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
                  <td>{{$item->A_paterno}}</td>
                  <td>{{$item->Nom_pat}}</td>
                  <td>{{$item->Nom_con}}</td>
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
            <button type="button" id="btnEliminar" class="btn btn-danger "><i class="fa fa-times"></i> Eliminar</button>
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
  <div class="modal fade" id="modalAsignar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <select class="form-control" id="idUsuarios">
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

@endsection  


@section('javascript')
    <script src="{{asset('/public/js/campo/AsignarMuestreo.js')}}"></script>
    <script src="{{asset('/public/js/libs/componentes.js')}}"></script>
    <script src="{{asset('/public/js/libs/tablas.js')}}"></script>
@stop

