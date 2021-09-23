@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="far fa-eye"></i>
    Lote
  </h6>
 
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
          <div class="row">
              
            <div class="col-md-3">
                <div class="form-group">
                  <label for="exampleFormControSelect1">Tipo formula</label>
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">Asignado</option>
                        <option value="2">Sin asignar</option>
                      </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Fecha lote</label>
                    <input type="date" class="form-control" placeholder="Fecha lote">
                </div>
              </div>


              <div class="col-md-2">
                <button class="btn btn-success">Buscar</button>
              </div>

          </div>
      </div>
    

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#modalProbar" class="btn btn-info">Crear lote</button>
                </div>
            </div>
            <table class="table" id="tableObservacion"> 
                <thead>
                  <tr>
                    <th scope="col">Cerrado</th>
                    <th scope="col">RcpLoteAnalisis</th>
                    <th scope="col">Formula</th>
                    <th scope="col">TipoFormulaAnalisis</th>
                    <th scope="col">FechaLote</th>
                    <th scope="col">FechaHora</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
        </div>
      </div>
</div>

 <!-- Modal -->
 <div class="modal fade" id="modalProbar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">FLAMA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist"> 
              <li class="nav-item" role="menu">
                <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true">Formulas Globales</a>
              </li>
              <li class="nav-item" role="menu">
                <a class="nav-link" id="equipo-tab" data-toggle="tab" href="#equipo" role="tab" aria-controls="equipo" aria-selected="false">Equipo</a>
              </li>
              <li class="nav-item" role="menu">
                <a class="nav-link" id="procedimiento-tab" data-toggle="tab" href="#procedimiento" role="tab" aria-controls="procedimiento" aria-selected="false">Procedimiento/Validación</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade  active" id="formulaGlobal" role="tabpanel" aria-labelledby="formulaGlobal-tab">  
              <h1>Formulas globales</h1>
              </div> 
               
              <div class="tab-pane fade" id="equipo" role="tabpanel" aria-labelledby="equipo-tab">  
              <h1>Equipo</h1>
              </div> 
            
              <div class="tab-pane fade" id="procedimiento" role="tabpanel" aria-labelledby="procedimiento-tab">  
              <h1>Procedimiento / Validación</h1>
              </div> 
            </div>


          </div>
          
          <div class="col-md-12">
            <div id="inputVar">

            </div>
          </div>
         
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      
      </div>
    </div>
  </div>
</div>

  @stop

  @section('css')
  <link rel="stylesheet" href="{{ asset('css/laboratorio/observacion.css')}}">
  @endsection

  @section('javascript')
  <script src="{{asset('js/laboratorio/observacion.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection