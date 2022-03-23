<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/style.css')}}">
 
    <title>Hoja de Campo </title>
</head>
<body style="font-size: 10px"> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless table-sm colorBorde">
                    <tr>
                        <td class="negrita"><center>HOJA DE CAMPO Y CADENA DE CUSTODIA EXTERNA</center></td> 
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                <div>                
                    <div width="50%" style="float: left">
                        1. DATOS GENERALES
                    </div>
                    <div class="justifyRight" width="50%" style="float: left;">
                        {PAGENO} / {nbpg}
                    </div>                    
                </div>                                
            </div>
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}">
                    <tr>
                        <td class="bordesTabla">Num. de muestra</td>
                        <td class="negrita bordesTablaSupInfDer">{{$model->Folio_servicio}}</td>
                        <td class="bordesTablaSupInfDer">No DE ORDEN</td>
                        <td class="bordesTablaSupInfDer justifyCenter negrita">{{@$numOrden->Folio_servicio}}</td>
                        <td class="bordesTablaSupInfDer">FECHA DE MUESTREO</td>
                        <td class="bordesTablaSupInfDer justifyCenter negrita">{{\Carbon\Carbon::parse(@$model->Fecha_muestreo)->format('d/m/Y')}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">NORMA APLICABLE</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="3">{{$model->Clave_norma}}</td>
                        <td class="bordesTablaInfIzqDer">MATRIZ</td>
                        <td class="justifyCenter negrita bordesTablaInfIzqDer">{{$model->Descarga}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">EMPRESA</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">{{$model->Empresa_suc}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">DIRECCION</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">{{$model->Direccion}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">PUNTO DE MUESTREO</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">{{$punto->Punto_muestreo}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                2. RECIPIENTES UTILIZADOS
            </div>
            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}">
                    <thead>
                        <tr>
                            <td class="negrita justifyCenter bordesTabla">#</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">ANALISIS</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">PARAMETRO</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">ENVASE</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">VOLUMEN</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">VOLUMEN</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">PRESERVACION</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">TEMPERATURA °C</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">SI/NO</td>
                        </tr>
                    </thead>

                    <tbody>                                           
                        @for ($i = 0; $i < $paramSolicitudLength; $i++)
                            <tr>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{@$envasesArray[$i]->Id_env}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{@$envasesArray[$i]->Area}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{@$envasesArray[$i]->Parametro}}</td>
                                <td class="bordesTablaInfIzqDer">{{@$envasesArray[$i]->Nombre}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{@$envasesArray[$i]->Volumen}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{@$envasesArray[$i]->Unidad}}</td>
                                <td class="bordesTablaInfIzqDer">{{@$envasesArray[$i]->Preservacion}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{@$envasesArray[$i]->Preservacion}}</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">SI</td>
                            </tr>
                        @endfor                        
                    </tbody>
                    
                </table>
            </div>
            <div class="col-12 negrita">
                3. DATOS DE CAMPO
            </div>
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}">
                    <tr>
                      <td class="bordesTabla justifyCenter">No DE MUESTRAS</td>
                      <td class="bordesTablaSupInfDer justifyCenter">FECHA Y HORA DE MUESTREO</td>
                      <td class="bordesTablaSupInfDer justifyCenter">GASTO</td>
                      <td class="bordesTablaSupInfDer justifyCenter">MAT. FLOT.</td>
                      <td class="bordesTablaSupInfDer justifyCenter">PH</td>
                      <td class="bordesTablaSupInfDer justifyCenter">PUNTO DE MUESTREO</td>
                      <td class="bordesTablaSupInfDer justifyCenter">TEMP AMB C°</td>
                      <td class="bordesTablaSupInfDer justifyCenter">TEMP AGUA C°</td>
                      <td class="bordesTablaSupInfDer justifyCenter">OLOR</td>
                      <td class="bordesTablaSupInfDer justifyCenter">COLOR</td>
                      <td class="bordesTablaSupInfDer justifyCenter">COND</td>
                    </tr>
                    @for ($i = 0; $i < $model->Num_tomas; $i++)
                        <tr>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$i + 1}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{\Carbon\Carbon::parse(@$phMuestra[$i]->Fecha)->format('d/m/Y')}} 
                                @php
                                    $fecha = $phMuestra[$i]->Fecha;
                                    $hora = date("h:j:s", strtotime($fecha));
                                    echo $hora
                                @endphp
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$gastoMuestra[$i]->Promedio}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$phMuestra[$i]->Materia}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$phMuestra[$i]->Promedio}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$punto->Punto_muestreo}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$tempMuestra[$i]->Promedio}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$tempMuestra[$i]->Promedio}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$phMuestra[$i]->Olor}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$phMuestra[$i]->Color}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{$conMuestra[$i]->Promedio}}</td>
                        </tr>
                    @endfor
                </table>
            </div>
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}" width="100%">
                    <tr>
                        <td class="bordesTablaInfIzqDer justifyCenter" width="50%">SUPERVICIÓN DEL MUESTREO (NOMBRE Y FIRMA)</td>
                        <td class="bordesTablaInfDer justifyCenter" width="50%">RESPONSABLE DEL MUESTREO (NOMBRE Y FIRMA)</td>                        
                    </tr>

                    <tr>
                        <td rowspan="2" class="bordesTablaInfIzqDer justifyCenter"><span class="negrita">{{$muestreador->name}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"></td>
                        <td rowspan="2" class="bordesTablaInfDer justifyCenter"><span class="negrita">{{$muestreador->name}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{url("/public/storage")."/".$firmaRes->firma}}"></td>                      
                    </tr>                    

                    {{-- <tr style="background-color: aquamarine">
                        <td class="negrita justifyCenter">{{$muestreador->name}}</td>
                        <td class="negrita justifyCenter">{{$muestreador->name}}</td>
                    </tr> --}}
                </table>
            </div>
            <div class="col-12 negrita">
                4. ENTREGA A LABORATORIO
            </div>
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}" width="100%">
                    <tr>
                      <td class="bordesTabla justifyCenter" colspan="2"><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="https://sistemaacama.com.mx/public/storage/users/January2022/3hR0dNwIyWQiodmdxvLX.png"></td>
                      <td class="bordesTabla justifyCenter" colspan="2"><img style="width: auto; height: auto; max-width: 100px; max-height: 80px;" src="{{url("/public/storage")."/".$firmaRes->firma}}"></td>                      
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer justifyCenter" colspan="2">RECEPCION EN EL LABORATORIO</td>
                        <td class="bordesTablaSupInfDer justifyCenter" colspan="2">ENTREGA LA(S) MUESTRA(S)</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="bordesTablaInfIzqDer justifyCenter">{{$muestreador->name}} <br> <span class="fontSize7">Nombre y Firma</span></td>
                        <td colspan="2" class="bordesTablaSupInfDer justifyCenter">{{$muestreador->name}} <br> <span class="fontSize7">Nombre y Firma</span></td>
                    </tr>

                    <tr>
                        <td colspan="2" class="bordesTablaInfIzqDer">Fecha y hora de recepción en Lab:</td>
                        <td colspan="2" class="bordesTablaSupInfDer">Fecha y hora de conformación de la muestra: </td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzq">PH MUESTRA COMPUESTA:</td>
                        <td class="bordesTablaInf">VOLUMEN MUESTRA COMPUESTA:</td>
                        <td class="bordesTablaInfDer" colspan="2">TEMPERATURA MUESTRA COMPUESTA:</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer" colspan="4">OBSERVACIONES:</td>
                    </tr>

                    <tr class="bordesTablaInfIzqDer">
                        <td colspan="4">{{@$model->Observacion}}</td>
                    </tr>
                </table>
            </div>
            
            <br>

            <div class="col-md-12" style="border:1px solid">
            </div>

            <div class="col-md-12">
                <table class="table table-sm fontSize7" width="100%">
                    <tr>
                      <td class="anchoColumna">10 Sur No. 7301, Col. Loma linda C.P 72477</td>
                      <td class="justifyCenter">PUEBLA, PUE.</td>
                      <td class="justifyCenter">Email:labacama@prodigy.net.mx</td>
                      <td class="justifyRight">Revisión, 8 Valido desde Mayo 15 01</td>                                            
                    </tr>

                    <tr>
                        <td class="anchoColumna" style="border: 0">Tels (222) 2456972 / 7555005 / 7555014 Fax</td>
                    </tr>
                </table>
            </div>                    
        </div>
    </div>
</body>
</html> 