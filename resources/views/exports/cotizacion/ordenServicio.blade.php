<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">
 
    <title>Solicitud {{$model->Folio_servicio}}</title>
</head>
<body> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col align-self-end">
                <p align="right">No DE SERVICIO: {{$model->Folio_servicio}}<p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 25%" class="">Nombre de la empresa</td>
                        <td class="" style="height: 50px;border: 1px solid;">
                            {{$model->Empresa}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Dirección</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{$model->Direccion}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Contacto</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{$model->Nom_con}} {{$model->Nom_pat}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Teléfono</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{$cotizacion->Telefono}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%" style="height: 50px;">Servicio</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{$model->Servicio}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Norma</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{$model->Clave_norma}}
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
                            <td style="height: 200px;border: 1px solid; color:red" class="border">
                                @php
                                    $cont = 0;
                                @endphp
                                @foreach ($parametros as $item)
                                    @if ($item->Extra != 0)
                                        @php
                                        $cont++;    
                                        @endphp
                                        {{$item->Parametro}}
                                    @endif    
                                @endforeach
                                @if ($cont == 0)
                                    @php
                                        echo "Sin parametros especiales";
                                    @endphp
                                @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Fecha de muestreo</td>
                        <td style="height: 50px;border: 1px solid;" class="border">
                            {{$model->Fecha_muestreo}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%">Observaciones</td>
                        <td style="height: 80px;border: 1px solid;" class="border">
                                {{$model->Observacion}}
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