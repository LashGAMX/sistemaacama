@extends('voyager::master')
@section('page_header')
@stop
@section('content')
<style>
    .hidden-column {
        display: none;
    }
</style>
<div class="container-fluid">


    
  <input type="text" hidden id="rol" value="{{Auth::user()->role->id}}">
    <div class="row" style="margin-bottom: -30px">
        <div class="col-md-12">
            <div class="row">
                <!-- Parte de Encabezado-->


                <div class="col-md-12">

                    <div class="row">
                        <div class="col-12">
                            <table class="table" style="width: 100%">
                                <tr>

                                <tr>
                                    <td>
                                        <button id="btnCreate" class="btn btn-success"><i class="voyager-plus"></i>
                                            Crear</button>
                                    </td>
                                    <td>
                                        <button id="btnEdit" class="btn btn-warning"><i class="voyager-edit"></i>
                                            Editar</button>
                                    </td>
                                    <td>
                                        <button id="btnImprimir" class="btn btn-info"><i
                                                class="voyager-documentation"></i> Imprimir</button>
                                    </td>
                                    <td>
                                       <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="mostrarCanceladas">
                                        <label class="form-check-label" for="mostrarCanceladas">Mostrar solo canceladas</label>
                                       </div>
                                    </td>
                                  

                                    <td>
                                        @switch(Auth::user()->id)
                                        @case(1)
                                        @case(36)
                                        @case(101)
                                        @case(107)
                                        @case(100)
                                        @case(31)
                                        @case(65)
                                        <button id="btnDuplicar" class="btn btn-info"><i class="voyager-file-text"></i>
                                            Duplicar Solicitud</button>
                                        &nbsp;
                                        <button id="btnCancelar" class="btn btn-danger"><i class="fas fa-delete"></i>
                                            Cancelar</button>
                                        @break
                                        @default
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
           <table id="tablaSolicitud" class="table table-bordered">
       <thead class="table-dark">
    <tr>
        <th style="width: 8px;">Id</th>
        <th style="width: 10px;">Estado</th>
        <th style="width: 10px;">Folio Servicio</th>
        <th style="width: 10px;">Folio Cotizacion</th>
        <th style="width: 10px;">Fecha Muestreo</th>
        <th style="width: 20px;">Nombre</th>
        <th style="width: 10px;">Clave Norma</th>
        <th style="width: 10px;">Tipo Descarga</th>
        <th style="width: 15px;">Creado Por</th>
        <th style="width: 10px;">Fecha Creación</th>
        <th style="width: 15px;">Actualizado Por</th>
        <th style="width: 10px;">Fecha Actualizado</th>
        </tr>
    <tr>
        <th><input type="text" placeholder="Buscar Id" /></th>
        <th><input type="text" placeholder="Buscar Estado" /></th>
        <th><input type="text" placeholder="Buscar Folio Servicio" /></th>
        <th><input type="text" placeholder="Buscar Folio" /></th>
        <th><input type="text" placeholder="Buscar Fecha Muestreo" /></th>
        <th><input type="text" placeholder="Buscar Nombre" /></th>
        <th><input type="text" placeholder="Buscar Clave Norma" /></th>
        <th><input type="text" placeholder="Buscar Descarga" /></th>
        <th><input type="text" placeholder="Buscar Creador" /></th>
        <th><input type="text" placeholder="Buscar Fecha Creación" /></th>
        <th><input type="text" placeholder="Buscar Actualizado" /></th>
        <th><input type="text" placeholder="Buscar Fecha Actualizacion" /></th>
    </tr>
</thead>

            <tbody></tbody>
           </table>



        </div>
    </div>
    </div>
    @endsection
    @section('javascript')
    <script src="{{ asset('public/js/cotizacion/solicitud2.js')}}?v=1.0.0"></script>
    @stop