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
                        <div class="col-12">
                            <table class="table" style="width: 100%">
                                <tr>
                                    <td>
                                        <label for="">Cliente</label>
                                        <!-- <input type="text" class="form-control" id="cliente" placeholder="Nombre Cliente"> -->
                                        <select class="form-control select2Data" style="width: 100%" id="cliente">
                                            <option value="0">Sin seleccionar</option>
                                        </select>
                                    </td>
                                    <td>
                                        <label for="Folio">Folio</label>
                                        <input type="text" class="form-control" id="folio" placeholder="Folio">
                                    </td>
                                    <td>
                                        <label for="norma">Normas</label>
                                        <select class="form-control select2" style="width: 100%" id="norma">
                                            <option value="0">Sin seleccionar</option>
                                            @foreach ($norma as $item)
                                            <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-success" id="btnBuscar">Buscar</button>
                                    </td>
                                </tr>
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
                            @php
                            if($item->Cancelado == 1){
                            $class = "bg-danger";
                            }else{
                            $class = "";
                            }
                            @endphp
                            <tr>
                                <td class="{{$class}}">{{$item->Id_cotizacion}}</td>
                                <td class="{{$class}}">
                                    @foreach ($estado as $item2)
                                    @if ($item->Estado_cotizacion == $item2->Id_estado)
                                    {{$item2->Estado}}
                                    @endif
                                    @endforeach
                                </td>
                                <td class="{{$class}}">{{$item->Folio_servicio}}</td>
                                <td class="{{$class}}">{{$item->Folio}}</td>
                                <td class="{{$class}}">{{$item->Fecha_muestreo}}</td>
                                <td class="{{$class}}">{{$item->Nombre}}</td>
                                <td class="{{$class}}">
                                    @foreach ($norma as $item2)
                                    @if ($item->Id_norma == $item2->Id_norma)
                                    {{$item2->Clave_norma}}
                                    @endif
                                    @endforeach
                                </td>
                                <td class="{{$class}}">
                                    @foreach ($descarga as $item2)
                                    @if ($item->Tipo_descarga == $item2->Id_tipo)
                                    {{$item2->Descarga}}
                                    @endif
                                    @endforeach
                                </td>
                                <td class="{{$class}}">
                                    @foreach ($usuario as $item2)
                                    @if ($item->Creado_por == $item2->id)
                                    {{$item2->name}}
                                    @endif
                                    @endforeach
                                </td>
                                <td class="{{$class}}">{{$item->created_at}}</td>
                                <td class="{{$class}}">
                                    @foreach ($usuario as $item2)
                                    @if ($item->Actualizado_por == $item2->id)
                                    {{$item2->name}}
                                    @endif
                                    @endforeach
                                </td>
                                <td class="{{$class}}">{{$item->updated_at}}</td>
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
<script src="{{ asset('public/js/cotizacion/solicitud.js')}}?v=1.0.6"></script>
@stop