
@extends('voyager::master')

@section('content') 

  @section('page_header')
  <h6 class="page-title">
    <i class="voyager-window-list"></i>
    Indicadores
</h6>
  @stop
<div class = "container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row justify-content-end">
        <div class="col-md-2">
          <button class="btn-success" data-toggle="modal" data-target="#modalCrearSeguimiento"><i class="fas fa-plus"></i> Nuevo Folio</button>
        </div>
        <div class="col-md-2">
          <button class="btn-info" id="btnGraficos"><i class="fas fa-chart-pie"></i> Graficas</button>
        </div>
      </div>
    </div>
    <div class="col-md-12">
        <table class="table" id="tableIndicadores">
           <thead>
              <tr>
                <th>Id</th>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha E.</th>
                <th>Fecha Re</th>
                <th># Descargas</th>
                <th>Hora Entrada</th>
                <th>Observaciones</th>
              </tr>
           </thead>
           <tbody>
              <tr>
                <td>1</td>
                <td>150-1/23</td>
                <td>MAGALUF  S.A. DE C.V </td>
                <td>NOM-001-2021</td>
                <td>6/1/2023</td>
                <td>6/3/2023</td>
                <td>3</td>
                <td>9:00</td>
                <td>MUESTRA SIN HDC</td>
              </tr>
              <tr style="background: #DC3545;color:aliceblue">
                <td>2</td>
                <td>152-1/23</td>
                <td>MAGALUF  S.A. DE C.V </td>
                <td>NOM-001-2021</td>
                <td>6/1/2023</td>
                <td>10/3/2023</td>
                <td>3</td>
                <td>9:00</td>
                <td>MUESTRA SIN HDC</td>
              </tr>
           </tbody>
        </table>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCrearSeguimiento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear Seguimiento</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="folio">Folio</label>
              <input type="text" class="form-control" id="folio">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="cliente">Cliente</label>
              <input type="text" class="form-control" id="cliente">
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="servicio">Servicio</label>
              <input type="text" class="form-control" id="servicio">
            </div>
          </div>
          <div class="col-md-12">
            <button class="btn-success"><i class="fas fa-save"></i> Crear seguimiento</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="detalleIndicadores" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rastreo de muestra: 150-1/23</h5>

      </div>
      <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Proceso</th>
                <th>Fecha salida</th>
                <th>Fecha Debio</th>
                <th>Retraso</th>
                <th>Observaciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Recepcion</td>
                <td>6/1/2023</td>
                <td>6/3/2023</td>
                <td>7</td>
                <td>ESTA CONDICIONANTE ES DE 7 DÍAS PARA NOM-001, PARA 127 14 DÍAS</td>
              </tr>
              <tr>
                <td>2</td>
                <td>SALIDA DEL LABORATORIO </td>
                <td>7/1/2023</td>
                <td>7/3/2023</td>
                <td>7</td>
                <td>ESTA CONDICIONANTE ES DE 7 DÍAS PARA NOM-001, PARA 127 14 DÍAS</td>
              </tr>
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>

@section('javascript')
  <script src="{{asset('/public/js/seguimiento/indicadores/indicadores.js')}}?v=0.0.1"></script>
@stop

@endsection
 
  