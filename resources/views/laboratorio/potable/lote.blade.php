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
              <select class="form-control" id="parametro">
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
        <div class="col-md-1">
            <button class="btn btn-success" id="btnCrear"><i class="fas fa-plus"></i> Crear</button>
        </div>
        <div class="col-md-1">
            <button class="btn btn-info" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalLote"><i class="fas fa-file-invoice"></i> Datos Lote</button>
        </div>
      </div>
    </div>
    
    <div class="col-md-12">
      <div class="" id="divTable">
        
      <table class="table" id="tablaLote"> 
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
<div class="modal fade" id="modalLote" tabindex="-1" aria-labelledby="modalLoteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 80%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLoteLabel">Datos lote</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" id="idLote" hidden>
        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="collapse" href="#formulaGeneral" role="button" aria-expanded="false" aria-controls="formulaGeneral">
                  Formula general
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#platilla" role="button" aria-expanded="false" aria-controls="platilla">
                  Datos plantilla
                </a>
              </li>
            </ul>

            <div class="collapse" id="formulaGeneral">
              <div class="card card-body">
                 <div class="row">
                    <div class="col-md-6">
                      <table class="table" style="width: 100%">
                          <thead>
                            <tr>
                              <th style="width: 10px">Id Lote</th>
                              <th>Formula</th>
                              <th>Expresión</th>
                              <th>Resultado</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><input style="width: 100px" id="idVal" disabled></td>
                              <td><input value="FACTOR REAL DUREZA" disabled></td>
                              <td><input value="FORMULA" disabled></td>
                              <td><input id="resVal" disabled></td>
                            </tr>                            
                          </tbody>
                      </table>
                    </div>
                    <div class="col-md-6">
                      <table class="table" style="width: 100%">
                        <thead>
                          <tr>
                            <th>Parametro</th>
                            <th>Descripción</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>A</td>
                            <td>mg CaCO0 EN LA SOLUCIÓN</td>
                            <td><input type="number" id="solA"></td>
                            <td>C</td>
                          </tr>
                          <tr>
                            <td>B</td>
                            <td>ml DE LA DISOLUCIÓN</td>
                            <td><input type="number" id="d1"></td>
                            <td>V</td>
                          </tr>
                          <tr>
                            <td>C</td>
                            <td>ml DE LA DISOLUCIÓN</td>
                            <td><input type="number" id="d2"></td>
                            <td>V</td>
                          </tr>
                          <tr>
                            <td>D</td>
                            <td>ml DE LA DISOLUCIÓN</td>
                            <td><input type="number" id="d3"></td>
                            <td>V</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                 </div>
              </div>
            </div>
            <div class="collapse" id="platilla">
              <div class="card card-body"> 
                <div class="row">
                  <div class="col-md-12">
                    <div id="divSummer">
                      <div id="summernote">
                           
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button class="btn btn-success" id="btnGuardarPlantilla">Guardar</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  @stop
  @section('javascript')
  <!-- include summernote css/js -->
  {{-- <script src="{{asset('//assets/summer/summernote.js')}}"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="{{asset('/public/js/laboratorio/potable/lote.js')}}"></script>
@endsection