<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="display: flex;">
                <img src="https://dev.sistemaacama.com.mx//storage/Logo_sin_fondo.png" style="width: 100;">
                <center><div style="font-size: 11px" class="verdeClaro">LABORATORIO DE ANÁLISIS DE CALIDAD <br> DEL AGUA Y MEDIOS AMBIENTALES S.A DE C.V</div></center>
            </div>
            <div class="col-md-12">
                <center><p class="">Solicitud de servicio de análisis</p></center>
            </div>
            <div class="col-md-12">
                <table class="table table-sm" style="font-size: 12px">
                    <tr>
                        <td style="width: 30%">Nombre de la empresa</td>
                        <td>
                            <div class="border" style="height: 50px;">
                                {{$model->Empresa}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Dirección</td>
                        <td>
                            <div class="border" style="height: 50px;">
                                {{$model->Direccion}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Contacto</td>
                        <td>
                            <div class="border" style="height: 50px;">
                                {{$model->Nom_con}} {{$model->Nom_pat}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Teléfono</td>
                        <td>
                            <div class="border" style="height: 40px;">
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%" style="height: 50px;">Servicio</td>
                        <td>
                            <div class="border" style="height: 50px;">
                                {{$model->Servicio}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Norma</td>
                        <td>
                            <div class="border" style="height: 50px;">
                                {{$model->Clave_norma}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Parametros</td>
                        <td>
                            <div class="border" style="height: 150px;"> 
                                @foreach ($parametros as $item)
                                    {{$item->Parametro}}
                                @endforeach 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Parametros especiales</td>
                        <td>
                            <div class="border" style="height: 100px;color:red">
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
                            <div class="border" style="height: 50px;">
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Observaciones</td>
                        <td>
                            <div class="border" style="height: 50px;">
                                {{$model->Observacion}}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">  <div class="dropdown-divider"></div></div>
            <div class="col-12">
                <table>
                    <tr>
                        <td style="font-size: 10px;">LAB-ACAMA, S.A DE C.V 10 Sur No. 7301, Col. Loma Linda, Puebla</td>
                        <td style="font-size: 10px;">REV 4 No. de Procedimiento: RE-11-004 Válido desde: 1-Septiembre</td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px;">Tel: (222) 245 6972 / 755 50 14/ 637 94 04</td>
                        <td style="font-size: 10px;">Fecha de Última Revisión: 30-Nov.2015</td>
                    </tr>                    
                </table>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>
</html> 