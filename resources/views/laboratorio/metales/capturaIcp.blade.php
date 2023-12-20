@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados Icp
  </h6>
  @stop

  <div class="container-fluid">
    <div class="row">
        <div class="col-md-4" id="divLote">
            <table class="table table-sm"> 
                <thead>
                  <tr>
                    <th># Lote</th>
                    <th>Fecha lote</th>
                    <th>Opc</th>
                    <th></th>
                    <th></th>
                  </tr> 
                </thead>
                <tbody>
        
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fechaLote">Fecha de lote</label>
                        <input type="date" class="form-control" id="fechaLote">
                    </div><br>
                    <div class="form-group">
                        <label for="fechaLote">Folio</label>
                        <input type="text" class="form-control" id="folio" placeholder="xxx-xx/xx">
                    </div><br>
                    <button class="btn-success" id="btnLiberar">Liberar</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-info" id="btnBuscarLote"><i class="fas fa-search"></i> Buscar</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success" id="btnCrearLote"><i class="fas fa-plus"></i> Crear</button>
                </div>
                <div class="col-md-3">
                  <form enctype="multipart/form-data" id="formuploadajax" >
                    @csrf
                    <input type="file" class="custom-file-input" id="file" name="file">
                    <input type="text" id="idLote" name="idLote" hidden>
                    <button type="submit" class="btn btn-success"><i class="fas fa-file-import"></i> Datos</button>
                  </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" id="divTablaControles">
            <table class="table" id="tablaControles">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Parametro</th>
                        <th>CPS Prom</th>
                        <th>Resultado</th>
                        <th>F/H Analisis </th>
                        <th>Tipo</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Datos bitacora</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                  <input type="text" id="tituloBit" hidden> 
                  <div id="divSummer"></div>
                  <input type="text" id="revBit" hidden>
              </div>
            </div>
        </div>
        <div class="modal-footer"> 
          <button type="button" id="btnSetPlantilla" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  @section('javascript')
  <script src="{{asset('public/js/laboratorio/metales/capturaIcp.js')}}?v=0.0.1"></script>
  {{-- <link href="{{asset('public/assets/summer/cyborg/summernote-lite-cyborg-libre.min.css')}}" rel="stylesheet"> --}}
  <script src="{{asset('/public/assets/summer/summernote.js')}}"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  @stop

@endsection    


