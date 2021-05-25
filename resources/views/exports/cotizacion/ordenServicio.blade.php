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
                <P align="right">FOLIO COTIZACIÓN: {{$model->Folio_servicio}}<p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td style="width: 30%" class="">Nombre de la empresa</td>
                        <td class="border">
                                {{$model->Empresa}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Dirección</td>
                        <td style="width: 70%" class="border">
                            {{$model->Direccion}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Contacto</td>
                        <td class="border">
                            {{$model->Nom_con}} {{$model->Nom_pat}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Teléfono</td>
                        <td class="border">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%" style="height: 50px;">Servicio</td>
                        <td class="border">
                            {{$model->Servicio}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Norma</td>
                        <td class="border">
                            {{$model->Clave_norma}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Parametros</td>
                        <td class="border">
                            @foreach ($parametros as $item)
                                    {{$item->Parametro}}
                                @endforeach 
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Parametros especiales</td>
                        <td class="border" style="color:red">
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
                        <td style="width: 30%">Fecha de muestreo</td>
                        <td class="border">
                            {{$model->Fecha_muestreo}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Observaciones</td>
                        <td class="border"> 
                                {{$model->Observacion}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
            <tr>
                <td>{!! DNS2D::getBarcodeHTML(url('clientes/orden_servicio/'.$model->Id_cotizacion), 'QRCODE') !!}</td>
            </tr>
        </table>
        {{-- <div class="col-md-12">
            <div style="width: 100px;height: 100px;">
                {!! DNS2D::getBarcodeHTML($model->Folio_servicio, 'QRCODE') !!}
            </div>
            <div style="width: 100px;height: 100px;">
                <div>{!! DNS1D::getBarcodeHTML($model->Folio_servicio, 'C39') !!}</div></br>
            </div>
        </div> --}}
    </div>
</body>
</html> 