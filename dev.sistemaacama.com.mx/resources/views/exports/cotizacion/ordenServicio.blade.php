<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">
 
    <title>Solicitud {{@$model->Folio_servicio}}</title>
</head>
<body> 
    <div class="container" id="pag">
        <div class="row">
            <div class="">
                <p align="center"><strong>SOLICITUD DE SERVICIO DE ANALISIS</strong><p>
            </div>
            <div class="col align-self-end">
                <p align="right">No DE SERVICIO: {{@$model->Folio_servicio}}<p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 25%" class="">Nombre de la empresa</td>
                        <td class="" style="height: 50px;border: 1px solid;">
                            {{@$cliente->Empresa}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Dirección</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            @if ($model->Siralab != 1)
                                {{@$direccion->Direccion}}
                            @else
                                {{@$direccion->Calle}} {{@$direccion->Num_exterior}} {{@$direccion->Num_interior}} {{@$direccion->Colonia}} {{@$direccion->NomMunicipio}} {{@$direccion->Estado}} CP: {{@$direccion->CP}}
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td style="width: 25%">Contacto</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{@$contacto->Nombre}} 
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Teléfono</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{@$contacto->Telefono}} 
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%" style="height: 50px;">Servicio</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            @if (@$cotizacion->Num_tomas == 1)
                            {{@$cotizacion->Servicio}} {{@$cotizacion->Tipo_muestra}} 
                            @else
                            {{@$cotizacion->Servicio}} ({{@$frecuenciaMuestreo->Frecuencia_muestreo}})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Norma</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{@$norma->Clave_norma}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Parametros</td>
                        <td style="height: 200px;border: 1px solid;" class="border">
                            @foreach ($parametros as $item)
                                {{$item->Parametro}}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Parametros especiales</td>
                            <td style="height: 100px;border: 1px solid; color:red" class="border">
                                @if ($extra->count()) 
                                    @foreach ($extra as $item)
                                        {{$item->Parametro}}
                                    @endforeach
                                @else
                                    @php
                                        echo "Sin parametros especiales"; 
                                    @endphp
                                @endif
                            </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Fecha de muestreo</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{\Carbon\Carbon::parse(@$model->Fecha_muestreo)->format('d/m/Y')}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Observaciones</td>
                        <td style="height: 80px;border: 1px solid;" class="border">
                                {{@$model->Observacion}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Puntos de muestreo</td>
                        <td style="height: 80px;border: 1px solid;" class="border">
                            @php 
                                $cont = 1;
                            @endphp
                            @foreach ($puntos as $item)
                                {{$cont."- "}}{{$item->Punto}}
                                @php
                                    $cont++; 
                                @endphp
                            @endforeach
                        </td>
                    </tr>
                </table>
                
            </div>
            <div class="col-md-12">
                
            </div>
        </div>
        
    </div>
</body>
</html> 