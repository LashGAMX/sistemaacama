<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/style.css')}}">
 
    <title>Solicitud </title>
</head>
<body> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <tr>
                        <td class="negrita"><center>HOJA DE CAMPO Y CADENA DE CUSTODIA EXTERNA</center></td> 
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                1. DATOS GENERALES
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <tr>
                        <td>Num. de muestra</td>
                        <td>{{$model->Folio_servicio}}</td>
                        <td>No DE ORDEN</td>
                        <td>{{$model->Folio_servicio}}</td>
                        <td>FECHA DE MUESTREO</td>
                        <td>{{$model->Fecha_muestreo}}</td>
                    </tr>
                    <tr>
                        <td>NORMA APLICABLE</td>
                        <td colspan="3">{{$model->Clave_norma}}</td>
                        <td>MATRIZ</td>
                        <td>{{$model->Descarga}}</td>
                    </tr>
                    <tr>
                        <td>EMPRESA</td>
                        <td colspan="5">{{$model->Empresa_suc}}</td>
                    </tr>
                    <tr>
                        <td>DIRECCION</td>
                        <td colspan="5">{{$model->Direccion}}</td>
                    </tr>
                    <tr>
                        <td>PUNTO DE MUESTREO</td>
                        <td colspan="5">{{$punto->Punto_muestreo}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                2. RECIPIENTES UTILIZADOS
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <tr>
                        <td>#</td>
                        <td>ANALISIS</td>
                        <td>PARAMETRO</td>
                        <td>ENVASE</td>
                        <td>VOLUMEN</td>
                        <td>PRESERVACION</td>
                        <td>TEMPERATIRA °C</td>
                        <td>SI/NO</td>
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                3. DATOS DE CAMPO
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <tr>
                      <td>No DE MUESTRAS</td>
                      <td>FECHA Y HORA DE MUESTREO</td>
                      <td>GASTO</td>
                      <td>MAT. FLOT.</td>
                      <td>PH</td>
                      <td>PUNTO DE MUESTREO</td>
                      <td>TEMP AMB C°</td>
                      <td>OLOR</td>
                      <td>COLOR</td>
                      <td>COND</td>
                    </tr>
                    @for ($i = 0; $i < $model->Num_tomas; $i++)
                        <tr>
                            <td>{{$i + 1}}</td>
                            <td>{{$phMuestra[$i]->Fecha}}</td>
                            <td>{{$gastoMuestra[$i]->Promedio}}</td>
                            <td>{{$phMuestra[$i]->Materia}}</td>
                            <td>{{$phMuestra[$i]->Promedio}}</td>
                            <td>{{$punto->Punto_muestreo}}</td>
                            <td>{{$tempMuestra[$i]->Promedio}}</td>
                            <td>{{$phMuestra[$i]->Olor}}</td>
                            <td>{{$phMuestra[$i]->Color}}</td>
                            <td>{{$conMuestra[$i]->Promedio}}</td>
                        </tr>
                    @endfor
                </table>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <tr>
                      <td>RESPONSABLE DEL MUESTREO (NOMBRE Y FIRMA)</td>
                      <td rowspan="2"><img style="width: 100px;height: 80px;" src="https://dev.sistemaacama.com.mx//storage/users/May2021/ZApPzkPb5RId7WHFQHon.jpeg"></td>
                      <td>SUPERVICIÓN DEL MUESTREO (NOMBRE)</td>
                      <td rowspan="2"></td>
                    </tr>
                    <tr>
                        <td>{{$muestreador->name}}</td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                4. ENTREGA A LABORATORIO
            </div>
            <div class="col-md-12">
                <table class="table table-bordered table-sm">
                    <tr>
                      <td>RECEPCION EN EL LABORATORIO</td>
                      <td rowspan="2"></td>
                      <td>ENTREGA LA(S) MUESTRA(S)</td>
                      <td rowspan="2"></td>
                    </tr>
                    <tr>
                        <td>---</td>
                        <td>---</td>
                    </tr>
                    <tr>
                        <td colspan="2">Fecha y hora de recepción en Lab:</td>
                        <td colspan="2">Fecha y hora de conformación de la muestra: </td>
                    </tr>
                    <tr>
                        <td>PH MUESTRA COMPUESTA:</td>
                        <td>VOLUMEN MUESTRA COMPUESTA:</td>
                        <td colspan="2">TEMPERATURA MUESTRA COMPUESTA</td>
                    </tr>
                    <tr>
                        <td colspan="4">OBSERVACIONES:</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12" style="border:1px solid">
            </div>
            <div class="col-md-12">
                <p>10 Sur No. 7301, Col. Loma linda C.P 72477</p>
            </div>
            <div class="col-md-12">
                <p>Tels (222) 2456972 / 7555005 / 7555014 Fax </p>
            </div>
            <div>

            </div>
        </div>
    </div>
</body>
</html> 