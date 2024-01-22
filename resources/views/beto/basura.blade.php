@extends('voyager::master')
@section('page_header')
@stop
@section('content')

<body>
    
    <h1>    Eliminar basura de bitacoras </h1>
    <br>
    <div class="formulario">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        
                        <input type="text" id="folio" placeholder="Folio">
                        <input type="number" id="parametro" placeholder="Id Parametro">
                        <input type="button" value="Buscar" id="buscar" onclick="buscar()">
                        <input type="button" value="ELIMINAR" id="Eliniar" onclick="eliminar()">
                        <input type="button" value="REASIGNAR" id="reasiganar" onclick="reasignar()">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="tabla">
        <div>
            <table class="table" id="tableCodigoParametro">
                <thead>
                   <th>Id</th>
                   <th>Id_parametro</th>
                   <th>Resultado</th> 
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
<br>
    <div class="table" id="tablaLote">
        <div>
            <table border 1 id="loteDetalle">
                <thead>
                   
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</body>

@endsection
@section('javascript')
<script src="{{asset('/public/js/recursos/basura.js')}}?v=0.0.5"></script>

@stop