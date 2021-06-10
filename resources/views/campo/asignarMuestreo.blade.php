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
            <th>Creado por</th>
            <th>Fecha Creacción</th>
            <th>Actualizado por</th>
            <th>Fecha Actualización</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($model as $item) 
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
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-2">
            <button type="button" id="btnAsignar" data-toggle="modal" data-target="#asignar" class="btn btn-success "><i class="voyager-list-add"></i> Asignar</button>
          </div>
          {{-- <div class="col-md-2">
              <label>Folio: {{ $folio }}</label>
          </div>
          <div class="col-md-2">
            <select class="form-control" id="idUsuarios">
              <option>Selecciona un usuario</option>
              @foreach ($usuarios as $item)
              <option value="{{$item->id}}">{{$item->name}}</option>
              @endforeach
              </select>
          </div> --}}
        </div>
      </div>
     </div>
    <div class="col-md-12">
      <table class="table table-sm" id="solicitudGenerada">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Punto de muestreo</th>
            <th>Captura</th>
            <th>Id muestreador</th>
            <th>Ap Paterno</th>
            <th>Ap Materno</th>
            <th>Nombre</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($generadas as $item) 
          <tr>
          <td>{{$item->Folio}}</td>
          <td>{{$item->Punto_muestreo}}</td>
          <td>{{$item->Captura}}</td>
          <td>{{$item->Id_muestreador}}</td>
          <td>{{$item->A_paterno}}</td>
          <td>{{$item->A_materno}}</td>
          <td>{{$item->Nombres}}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="asignar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

          <button class="btn btn-success" id="btnGuardar">Guardar</button>
        </div>
      </div>
    </div>
  </div>

@endsection  


@section('javascript')
    <script src="{{asset('js/campo/AsignarMuestreo.js')}}"></script>
    <script src="{{asset('js/libs/componentes.js')}}"></script>
    <script src="{{asset('js/libs/tablas.js')}}"></script>
@stop

