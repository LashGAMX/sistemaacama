<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">
    <title>Solicitud {{$model->Folio_servicio}}</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12" style="display: flex">
                <img src="https://dev.sistemaacama.com.mx//storage/Logo_sin_fondo.png" style="width: 100;">
                <center><div style="font-size: 11px" class="verdeClaro">LABORATORIO DE ANÁLISIS DE CALIDAD <br> DEL AGUA Y MEDIOS AMBIENTALES S.A DE C.V</div></center>
            </div>
        </div>
        <table>
            <tr>
                <td style="width: 30%">Nombre de la empresa</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        {{$model->Empresa}}    
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Dirección</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        {{$model->Direccion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Contacto</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        {{$model->Nom_con}} {{$model->Nom_pat}}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Teléfono</td>
                <td>
                    <div class="bordeTabla" style="height: 40px;">
                        
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%" style="height: 50px;">Servicio</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        {{$model->Servicio}}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Norma</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        {{$model->Clave_norma}}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Parametros</td>
                <td>
                    <div class="bordeTabla" style="height: 150px;"> 
                        @foreach ($parametros as $item)
                            {{$item->Parametro}}
                        @endforeach 
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Parametros especiales</td>
                <td>
                    <div class="bordeTabla" style="height: 100px;color:red">
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
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Fecha de muestreo</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Observaciones</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        {{$model->Observacion}}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">Observaciones</td>
                <td>
                    <div class="bordeTabla" style="height: 50px;">
                        
                    </div>
                </td>
            </tr>
        </table>
        <div class="col-md-12">
            <div style="width: 100px;height: 100px;">
                {!! DNS2D::getBarcodeHTML($model->Folio_servicio, 'QRCODE') !!}
            </div>
            <div style="width: 100px;height: 100px;">
                <div>{!! DNS1D::getBarcodeHTML($model->Folio_servicio, 'C39') !!}</div></br>
            </div>
        </div>
    </div>
</body>
</html> 