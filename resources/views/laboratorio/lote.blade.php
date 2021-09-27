@extends('voyager::master')

@section('content')

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
                    <select class="form-control">
                        <option value="0">Sin seleccionar</option>
                        <option value="1">Acreditados</option>
                        <option value="2">Metales alimentos</option>
                        <option value="3">Metales pesados en biosólidos</option>
                        <option value="4">Metales potable</option>
                        <option value="5">Metales purificadora</option>
                        <option value="6">Metales residual</option>
                        <option value="7">Miliequivalentes</option>
                        <option value="8">No acreditados</option>
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
                    <th scope="col">AnaFórmulaId</th>
                    <th scope="col">RcpLoteAnálisisId</th>
                    <th scope="col">Fórmula</th>
                    <th scope="col">TipoFórmulaBitácora</th>
                    <th scope="col">FechaLote</th>
                    <th scope="col">FechaHora</th>
                    <th scope="col">FechaNuevoLote</th>
                    <th scope="col">HoraNuevoLote</th>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLabel">FLAMA</h5>
        

        <label>ID Lote: <input type="text" id="idLote" size=10/></label>
        <label>Fecha Lote: <input type="datetime-local" id="fechaLote"/></label>                
        <button type="button" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-danger">Salir</button>
        <div class="form-check">
          <label class="form-check-label" for="flexCheckDefault">
            Cierre captura:
          </label>
          <input class="form-check-input" type="checkbox" id="cierreCaptura">        
        </div>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist"> 
              <li class="nav-item" role="menu">
                <a class="nav-link active" id="formulaGlobal-tab" data-toggle="tab" href="#formulaGlobal" role="tab" aria-controls="formulaGlobal" aria-selected="true">Fórmulas Globales</a>
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
                
                <div class="col-md-12">
                  <table class="table" id="tableFormulasGlobales"> 
                      <thead>
                        <tr>
                          <th scope="col">Fórmula</th>
                          <th scope="col">Fórmula</th>
                          <th scope="col">Resultado</th>
                          <th scope="col">Núm.Decimales</th>
                          <th scope="col">FechaInicio</th>                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td contenteditable="true"><input type="date-time" id="fechaInicio"></td>
                        </tr>
                      </tbody>
                    </table>


                    <table class="table" id="tableFormulasGlobalesValores"> 
                      <thead>
                        <tr>
                          <th scope="col">Parámetro</th>
                          <th scope="col">Descripción</th>
                          <th scope="col">Valor</th>
                          <th scope="col">Tipo</th>                          
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>                          
                        </tr>
                      </tbody>
                    </table>
                </div>

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
  <script src="{{asset('js/laboratorio/lote.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection