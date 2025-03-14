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
                <table id="tablaSolicitud" class="table table-bordered table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Id</th>
                            <th>Estado</th>
                            <th>Folio servicio</th>
                            <th>Folio cotización</th>
                            <th>Fecha Muestreo</th>
                            <th>Nombre cliente</th>
                            <th>Norma</th>
                            <th>Tipo descarga</th>
                            <th>Creado por</th>
                            <th>Fecha creación</th>
                            <th>C/</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($model as $item)
                        @php
                        $class = $item->Cancelado == 1 ? "bg-danger text-white" : "";
                        @endphp
                        <tr>
                            <td class="{{ $class }}">{{ $item->Id_cotizacion }}</td>
                            <td class="{{ $class }}">{{ $item->Cotizacion_estado->Estado ?? 'N/A' }}</td>
                            <td class="{{ $class }}">{{ $item->Folio_servicio }}</td>
                            <td class="{{ $class }}">{{ $item->Folio }}</td>
                            <td class="{{ $class }}">{{ $item->Fecha_muestreo }}</td>
                            <td class="{{ $class }}">
                                <textarea class="form-control" rows="1" readonly>{{ $item->Nombre ?? 'N/A' }}</textarea>
                            </td>
                            <td class="{{ $class }}">
                                <textarea class="form-control" rows="1"
                                    readonly>{{ $item->clavenorma->Clave_norma ?? 'N/A' }}</textarea>
                            </td>
                            <td class="{{ $class }}">{{ $item->descarga->Descarga ?? 'N/A' }}</td>
                            <td class="{{ $class }}">{{ $item->Creador->name ?? 'N/A' }}</td>
                            <td class="{{ $class }}">{{ $item->created_at }}</td>
                            <td class="hidden-column {{ $class }}">{{ $item->Cancelado }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <!-- Fin de la Tabla -->
            </div>
        </div>
    </div>
</div>

<div class="" id="divModal">

</div>
@endsection
@section('javascript')
<script src="{{ asset('public/js/cotizacion/solicitud2.js')}}?v=1.0.0"></script>
@stop