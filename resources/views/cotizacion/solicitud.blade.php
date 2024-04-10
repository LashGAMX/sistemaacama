@extends('voyager::master')
@section('page_header')
    @stop
@section('content') 
 
    <div class="container-fluid">
        <input type="text" hidden id="rol" value="{{Auth::user()->role->id}}">
        <div class="row" style="margin-bottom: -30px">
            <div class="col-md-12">
                <div class="row">
                    <!-- Parte de Encabezado-->
                   
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="cliente" placeholder="Nombre Cliente">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="folio" placeholder="Folio">
                        </div>
                        <div class="col-md-3">
                        <input type="text" class="form-control" id="norma" placeholder="Norma">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success" id="btnBuscar">Buscar</button>
                        </div>
                    </div>
                </div>
                
                    
                  <div class="col-md-12">
                   <div class="row">
                            <div class="col-md-1">
                                <button id="btnCreate" class="btn btn-success" ><i class="voyager-plus"></i> Crear</button>
                            </div>
                            {{-- <div class="col-md-2">
                                @if (Auth::user()->role->id != 13)
                                    <button id="btnCreateSinCot" class="btn btn-success" ><i class="voyager-plus"></i> Crear sin Cot</button>
                                @else
                                    <button id="btnCreateSinCot" class="btn btn-success" ><i class="voyager-plus"></i> Crear Orden</button>
                                @endif
                            </div> --}}
                            <div class="col-md-1">
                                <button id="btnEdit" class="btn btn-warning" ><i class="voyager-edit"></i> Editar</button>
                            </div>
                            <div class="col-md-2">
                                <button id="btnImprimir" class="btn btn-info" ><i class="voyager-documentation"></i> Imprimir</button>
                            </div>

                            <div class="col-md-2">
                                <button id="btnDuplicar" class="btn btn-info" ><i class="voyager-file-text"></i> Duplicar Solicitud</button>
                            </div>
                            <!-- <div class="col-md-2">
                                <button id="btnCancelar" class="btn btn-danger"><i class="fas fa-delete"></i> Cancelar</button>
                            </div> -->

                            <!-- <div class="col-md-1">
                                @if (Auth::user()->role->id != 13)
                                    <button id="btnGenFolio" class="btn btn-success" ><i class="voyager-file-text"></i> Entrada al lab</button>
                                @endif
                            </div> -->
                            </div>                        
                    </div>

                     <div id="divTabla">
                     <table id="tablaSolicitud" class="table">    
                        <thead>
                            <tr>
                                <th style="width: 10px">Id</th>
                                <th>Estado</th>
                                <th style="width: 30px">Folio servicio</th>
                                <th>Folio cotización</th>
                                <th>Fecha Muestreo</th>
                                <th>Nombre cliente</th>
                                <th>Norma</th>
                                <th>Tipo descarga</th>
                                <th>Creado por</th>
                                <th>Fecha creación</th>
                                <th>Actualizado por</th>
                                <th>Fecha actualización</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($model as $item)
                            <tr>
                                <td>{{$item->Id_cotizacion}}</td>
                                <td>
                                    @foreach ($estado as $item2)
                                        @if ($item->Estado_cotizacion == $item2->Id_estado)
                                            {{$item2->Estado}}       
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$item->Folio_servicio}}</td>
                                <td>{{$item->Folio}}</td>
                                <td>{{$item->Fecha_muestreo}}</td>
                                <td>{{$item->Nombre}}</td>
                                <td>
                                    @foreach ($norma as $item2)
                                        @if ($item->Id_norma == $item2->Id_norma)
                                            {{$item2->Clave_norma}}       
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($descarga as $item2)
                                        @if ($item->Tipo_descarga == $item2->Id_tipo)
                                            {{$item2->Descarga}}       
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($usuario as $item2)
                                        @if ($item->Creado_por == $item2->id)
                                            {{$item2->name}}       
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    @foreach ($usuario as $item2)
                                        @if ($item->Actualizado_por == $item2->id)
                                            {{$item2->name}}       
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{$item->updated_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                     </div>   
                    <!-- Fin de la Tabla --> 
                </div>
            </div>
        </div>
    </div>

    <div class="" id="divModal">

    </div>
@endsection 
    @section('javascript')
        <script src="{{ asset('public/js/cotizacion/solicitud.js')}}?v=1.0.5"></script>        
    @stop


    

