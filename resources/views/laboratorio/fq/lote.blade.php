@extends('voyager::master')

@section('content')
<link rel="stylesheet" href="{{asset('/public/assets/summer/summernote.min.css')}}">
  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-data"></i>    
    Lote
  </h6>
 
<div class="container-fluid">
  <div class="row"> 
    <div class="col-md-12">
      <div class="row">   
         
        <div class="col-md-3">
          <div class="form-group">
            <label for="exampleFormControSelect1">Tipo fórmula</label>
              <select class="form-control" id="tipo">
                @foreach($parametro as $item)
                  <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
              </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="">Fecha lote</label>
            <input type="date" id="fecha" class="form-control" placeholder="Fecha lote">
          </div>
        </div>
        
        <div class="col-md-2">
          <button class="btn btn-success" onclick="buscarLote()">Buscar</button>
          <button class="btn-info" type="button" id="btnPendiente" data-toggle="modal" data-target="#pendientes">Pendientes</button> &nbsp;
        </div>        
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="row"> 
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalCrearLote" class="btn btn-info">Crear lote</button>
        </div>
        <div class="col-md-3">
          <button class="btn btn-success" data-toggle="modal" data-target="#modalProbar" class="btn btn-info">Datos lote</button>
        </div>
        <div class="col-md-3">
          {{-- <button class="btn btn-success" data-toggle="modal" data-target="#modalFq2" class="btn btn-info">Nueva función</button> --}}
          # de lotes anteriores : <input type="text" id="numLotes">
        </div>
      </div>
      <div class="" id="divTable">
        
      <table class="table" id="tableObservacion"> 
        <thead>
          <tr>
            <th>#</th>
            <th>Tipo formula</th>
            <th>Fecha lote</th>
            <th>Fecha creacion</th>
            <th>Opc</th>
          </tr>
        </thead>
        <tbody>
                  
        </tbody>
      </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetalleLabel">Detalle lote: <input type="" id="idLote" style="border:none;width: 80%;"></h5>
      </div>
      <div class="modal-body">
       {{-- Inicio de Body  --}}
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">General</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Datos</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Plantilla</button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active" id="home" role="tabpanel" aria-labelledby="home-tab">
            Dato 1
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
           
        <!-- inicio tabla grasas   -->
        <div class="row">
        <h4>1. Calentamiento de Matraces</h4>
        <hr />
        <div class="col-md-12">
          <table class="table">
            <thead>
              <th>Masa constante</th>
              <th>Temperatura</th>
              <th>Hora entrada</th>
              <th>Hora salida</th>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><input type="text" id="temp1" /></td>
                <td><input type="datetime-local" id="entrada1" /></td>
                <td><input type="datetime-local" id="salida1" /></td>
              </tr>
              <tr>
                <td>2</td>
                <td><input type="text" id="temp2" /></td>
                <td><input type="datetime-local" id="entrada2" /></td>
                <td><input type="datetime-local" id="salida2" /></td>
              </tr>
              <tr>
                <td>3</td>
                <td><input type="text" id="temp3" /></td>
                <td><input type="datetime-local" id="entrada3" /></td>
                <td><input type="datetime-local" id="salida3" /></td>
              </tr>
            </tbody>
          </table>
          <h4>2. Enfriado de Matraces</h4>
          <hr />
          <table class="table">
            <thead>
              <th>Masa constante</th>
              <th>Hora entrada</th>
              <th>Hora salida</th>
              <th>Hora pesado de matraces</th>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><input type="datetime-local" id="2entrada1" /></td>
                <td><input type="datetime-local" id="2salida1" /></td>
                <td><input type="datetime-local" id="2pesado1" /></td>
              </tr>
              <tr>
                <td>2</td>
                <td><input type="datetime-local" id="2entrada2" /></td>
                <td><input type="datetime-local" id="2salida2" /></td>
                <td><input type="datetime-local" id="2pesado2" /></td>
              </tr>
              <tr>
                <td>3</td>
                <td><input type="datetime-local" id="2entrada3" /></td>
                <td><input type="datetime-local" id="2salida3" /></td>
                <td><input type="datetime-local" id="2pesado3" /></td>
              </tr>
            </tbody>
          </table>
          <h4>3. Secado de Cartuchos</h4>
          <hr />
          <table class="table">
            <thead>
              <th>Temperatura</th>
              <th>Hora entrada</th>
              <th>Hora salida</th>
            </thead>
            <tbody>
              <tr>
                <td><input type="number" id="3temperatura" /></td>
                <td><input type="datetime-local" id="3entrada" /></td>
                <td><input type="datetime-local" id="3salida" /></td>
              </tr>
            </tbody>
          </table>
          <h4>4. Tiempo de reflujo</h4>
          <hr />
          <table class="table">
            <thead>
              <th>Hora entrada</th>
              <th>Hora salida</th>
            </thead>
            <tbody>
              <tr>
                <td><input type="datetime-local" id="4entrada" /></td>
                <td><input type="datetime-local" id="4salida" /></td>
              </tr>
            </tbody>
          </table>
          <h4>5. Enfriado de matraces</h4>
          <hr />
          <table class="table">
            <thead>
              <th>Hora entrada</th>
              <th>Hora salida</th>
            </thead>
            <tbody>
              <tr>
                <td><input type="datetime-local" id="5entrada" /></td>
                <td><input type="datetime-local" id="5salida" /></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
        <button type="button" id="btnGuardarDetalleGasas" onclick="guardarDetalleGrasas()" class="btn btn-primary">Guardar</button>
      </div>
<!-- fin de tabla grasas -->

          </div>
          <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="row">
              <div class="col-md-12">
                <button id="btnBitacora" class="btn bg-success"><i class="fas fa-save"></i> Guardar</button>
              </div>
              <div class="col-md-12">
                <input type="text" id="tituloBit" hidden>
                <div id="divSummer"></div>
                <input type="text" id="revBit" hidden>
              </div>
            </div>
          </div>
        </div>
       {{-- Fin de body --}} 
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>



<!-- Modal Create Lote -->
<div class="modal fade" id="modalCrearLote" tabindex="-1" role="dialog" aria-labelledby="modalCrearLote" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear lote</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            
            <div class="form-group">
              <label for="exampleFormControSelect1">Tipo fórmula</label>
              <select class="form-control" id="tipoFormula">
                @foreach($parametro as $item)
                  <option value="{{$item->Id_parametro}}">({{$item->Id_parametro}}) {{$item->Parametro}} ({{$item->Tipo_formula}})</option>
                @endforeach
              </select>
            </div>
     
            <div class="col-md-12">
              <div class="form-group">
                <input type="date" id="fechaLote" class="form-control">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnCreateLote" onclick="createLote()" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
 


<!-- Modal Pendientes-->
<div class="modal fade" id="pendientes"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pendientes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="divPendientes">
        <table class="table">
          <thead>
            <tr>
              <th>Folio</th>
              <th>Parametro</th>
              <th>Fecha recepción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

  @stop

  @section('css')
    <link rel="stylesheet" href="{{ asset('/css/laboratorio/fq/lote.css')}}">    
  @endsection

  @section('javascript')
  <!-- include summernote css/js -->
  <script src="{{asset('/assets/summer/summernote.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('/public/js/laboratorio/fq/lote.js')}}"></script>
@endsection