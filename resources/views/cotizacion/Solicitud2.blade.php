@extends('voyager::master')
@section('page_header')
@stop
@section('content')
<<<<<<< HEAD
<style>
    .hidden-column {
        display: none;
    }
</style>
=======

>>>>>>> 2b914187672a51c20e1918251d5136fec63fe60b
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
<<<<<<< HEAD

=======
                                    <td>
                                    <button id="resetFilters" class="btn btn-secondary btn-sm">Resetear filtros</button>

                                    </td>
>>>>>>> 2b914187672a51c20e1918251d5136fec63fe60b
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
<<<<<<< HEAD
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
=======

                <table id="tablaSolicitud" class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%">Id</th>
                            <th style="width: 5%">Estado</th>
                            <th style="width: 5%">Folio servicio</th>
                            <th style="width: 5%">Folio cotización</th>
                            <th style="width: 5%">Fecha Muestreo</th>
                            <th style="width: 10%">Nombre cliente </th>
                            <th style="width: 10%">Norma</th>
                            <th style="width: 5%">Tipo descarga</th>
                            <th style="width: 10%">Creado por</th>
                            <th style="width: 5%">Fecha creación</th>
                            <th style="width: 10%">Actualizado por</th>
                            <th style="width: 5%">Fecha actualización</th>
>>>>>>> 2b914187672a51c20e1918251d5136fec63fe60b
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($model as $item)
                        @php
<<<<<<< HEAD
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
=======
                        if($item->Cancelado == 1){
                        $class = "bg-danger";
                        }else{
                        $class = "";
                        }
                        @endphp
                        <tr>
                            <td class="{{$class}}" style="width: 5%">{{$item->Id_cotizacion}}</td>
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
                            <td class="{{$class}}">
                                <textarea class="form-control" rows="2" readonly>{{$item->Nombre}}</textarea>
                            </td>

                            <td class="{{$class}}">
                                <textarea class="form-control" rows="2" readonly>
                                  @foreach ($norma as $item2)
                                            @if ($item->Id_norma == $item2->Id_norma)
                                                {{$item2->Clave_norma}}
                                            @endif
                                  @endforeach
                                </textarea>
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
>>>>>>> 2b914187672a51c20e1918251d5136fec63fe60b
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