@extends('voyager::master')
@section('page_header')
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="">Indicador</label>
                        <select id="selIndicador" class="form-control">
                            <option value="0">Sin seleccionar</option>
                            <option value="1">Solicitudes generadas</option>
                            <option value="2">Orden de servicio en proceso</option>
                            <option value="3">Cotizaciones creadas</option>
                            <option value="4">Recepción de muestras</option>
                            <option value="5">Informes impresos</option>
                            <option value="6">Creado vs impreso</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="">Fecha inicio</label>
                    <input type="date" id="fechaInicio" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="">Fecha fin</label>
                    <input type="date" id="fechaFin" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="">Norma</label>
                    <select id="norma" name="norma[]" multiple="multiple" class="form-control select2">
                    <option value="0" selected>Sin seleccionar</option>
                        @foreach ($norma as $item)
                            <option value="{{$item->Id_norma}}">{{$item->Clave_norma}}</option>
                        @endforeach
                    </select>
                </div>
              
                <div class="col-md-2">
                    <br>
                    <button class="btn btn-success" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div id="divIndicador">
                <canvas id="myChart"></canvas>
            </div>
        </div>
        <div class="col-md-2">
            <div id="divExtra">

            </div>
        </div>
    </div>
</div>



@endsection
@section('javascript')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('public/js/kpi/kpi.js') }}?v=0.0.1"></script>

@stop