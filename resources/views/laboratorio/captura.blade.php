@extends('voyager::master')

@section('content')

  @section('page_header')
  <h6 class="page-title"> 
    <i class="voyager-window-list"></i>
    Captura de resultados
  </h6>
 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Formula tipo</label>
                <select class="form-control">
                    <option value="0">Sin seleccionar</option>
                    <option value="1">Asignado</option>
                    <option value="2">Sin asignar</option>
                  </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Num. Tomas</label>
                <input type="text" class="form-control" placeholder="Num Toma">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Fecha análisis</label>
                <input type="date" class="form-control" placeholder="Num Toma">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success">Buscar</button>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <table class="table" id="tableAnalisis"> 
                        <thead>
                          <tr>
                            <th>Folio</th>
                            <th>Fecha lote</th>
                            <th>Total asignado</th>
                            <th>Total libre</th>
                            <th></th>
                            <th>Creado por</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                        </tbody>
                      </table>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3">
                            <p class="">Información global</p>
                        </div>
                        <div class="col-md-9">
                            <p class="">Información</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1">
                    <button class="btn btn-secondary">Ejecutar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary">Liberar</button>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary">Liberar todo</button>
                </div>
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                        <label class="form-check-label" for="defaultCheck1">
                          Blanco
                        </label>
                      </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-secondary">Generar controles</button>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-secondary">Duplicar</button>
                </div>
            </div>
            <table class="table" id="tableDatos2">
                <thead>
                    <tr>
                        <th>NumMuestra</th>
                        <th>NomCliente</th>
                        <th>PuntoMuestreo</th>
                        <th>Vol. Muestra E</th>
                        <th>X</th>
                        <th>Y</th>
                        <th>Z</th>
                        <th>Absorción
                            promedio
                        </th>
                        <th>Factor dilución D</th>
                        <th>Factor conversion G</th>
                        <th>Vol. disolución digerida v</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>
</div>
  @stop

  @section('javascript')
  <script src="{{asset('js/laboratorio/captura.js')}}"></script>
  <script src="{{asset('js/libs/componentes.js')}}"></script>
  <script src="{{asset('js/libs/tablas.js')}}"></script>
  @stop

@endsection  


