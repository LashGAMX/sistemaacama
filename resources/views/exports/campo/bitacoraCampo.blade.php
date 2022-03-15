<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/style.css')}}">
 
    <title>Bitácora de Campo </title>
</head>
<body> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="negrita"><center>NORMA DE MUESTREO {{$model->Clave_norma}}</center></td> 
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                Folio: {{$model->Folio_servicio}}
            </div>
            <div class="col-12 negrita">
                Fecha: {{$model->Fecha_muestreo}}
            </div>
            <div class="col-md-12" style="border:1px solid">
            </div>

            <div class="col-md-12">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td>Se realiza la Calibración Analitica Interna del equipo</td>
                        <td>MEDIDOR DE PH, TEMPERATURA Y CONDUCTIVIDAD</td>
                        <td>{{$campoGen->Marca}}</td>
                        <td>{{$campoGen->Equipo}}</td>
                    </tr>
                    <tr>
                        <td>ConNo. serie</td>
                        <td>{{$campoGen->Serie}}</td>
                        <td>a una temperatura de</td>
                        <td>{{$campoGen->Temperatura_a}} °C.</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7"><center>ph (Trazable)</center></td>
                                </tr>
                                    <tr>
                                       <td>pH</td>
                                       <td>Marca</td>
                                       <td>No. Lote</td>
                                       <td>1° Lectura</td>
                                       <td>2° Lectura</td>
                                       <td>3° Lectura</td>
                                       <td>+-0.05 unidades de pH y 0.03 entre lecturuas</td>
                                    </tr>
                                    @foreach ($phTrazable as $item)
                                        <tr>
                                            <td>7</td>
                                            <td>PERMONT</td>
                                            <td>100231</td>
                                            <td>{{$item->Lectura1}}</td>
                                            <td>{{$item->Lectura2}}</td>
                                            <td>{{$item->Lectura3}}</td>
                                            <td>{{$item->Estado}}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7"><center>ph (Calidad)</center></td>
                                </tr>
                                    <tr>
                                       <td>pH</td>
                                       <td>Marca</td>
                                       <td>No. Lote</td>
                                       <td>1° Lectura</td>
                                       <td>2° Lectura</td>
                                       <td>3° Lectura</td>
                                       <td>+-0.05 unidades de pH y 0.03 entre lecturuas</td>
                                    </tr>
                                    @foreach ($phTrazable as $item)
                                        <tr>
                                            <td>7</td>
                                            <td>PERMONT</td>
                                            <td>100231</td>
                                            <td>{{$item->Lectura1}}</td>
                                            <td>{{$item->Lectura2}}</td>
                                            <td>{{$item->Lectura3}}</td>
                                            <td>{{$item->Estado}}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7"><center>CONDUCTIVIDAD (Trazable)</center></td>
                                </tr>
                                    <tr>
                                       <td>Cloruro de
                                        potasio
                                        (μS/cm)</td>
                                       <td>Cloruro de
                                        potasio
                                        (μS/cm)</td>
                                       <td>No. Lote</td>
                                       <td>1° Lectura</td>
                                       <td>2° Lectura</td>
                                       <td>3° Lectura</td>
                                       <td>+- 5% de
                                        aceptación valor
                                        nominals</td>
                                    </tr>
                                    @foreach ($campoConTrazable as $item)
                                        <tr>
                                            <td>1412</td>
                                            <td>MERCK</td>
                                            <td>B1834438</td>
                                            <td>{{$item->Lectura1}}</td>
                                            <td>{{$item->Lectura2}}</td>
                                            <td>{{$item->Lectura3}}</td>
                                            <td>{{$item->Estado}}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </td>
                        <td>
                            <table class="table border table-sm">
                                <tr>
                                    <td colspan="7"><center>CONDUCTIVIDAD (Calidad)</center></td>
                                </tr>
                                    <tr>
                                       <td>Cloruro de
                                        potasio
                                        (μS/cm)</td>
                                       <td>Cloruro de
                                        potasio
                                        (μS/cm)</td>
                                       <td>No. Lote</td>
                                       <td>1° Lectura</td>
                                       <td>2° Lectura</td>
                                       <td>3° Lectura</td>
                                       <td>+- 5% de
                                        aceptación valor
                                        nominals</td>
                                    </tr>
                                    @foreach ($campoConTrazable as $item)
                                        <tr>
                                            <td>1412</td>
                                            <td>MERCK</td>
                                            <td>B1834438</td>
                                            <td>{{$item->Lectura1}}</td>
                                            <td>{{$item->Lectura2}}</td>
                                            <td>{{$item->Lectura3}}</td>
                                            <td>{{$item->Estado}}</td>
                                        </tr>
                                    @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
         
            <div class="col-12 negrita">
                Empresa
            </div>
            <div class="col-12">
                NOMBRE DE LA EMPRESA: {{$model->Empresa_suc}}
            </div>
            <div class="col-12">
                PUNTO DE MUESTREO: {{$punto->Punto_muestreo}}
            </div>

           


            </div>
        </div>
    </div>
</body>
</html> 