<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <img src="https://dev.sistemaacama.com.mx//storage/Logo_sin_fondo2.png" style="width: 100;">
            </div>
            <div class="col-md-12">
                <center><p class="">Solicitud de servicio de análisis</p></center>
            </div>
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td style="width: 30%">Nombre de la empresa</td>
                        <td>
                            <div class="border">
                                {{$model->Empresa}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Dirección</td>
                        <td>
                            <div class="border">
                                {{$model->Direccion}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Contacto</td>
                        <td>
                            <div class="border">
                                {{$model->Nom_con}} {{$model->Nom_pat}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Teléfono</td>
                        <td>
                            <div class="border">
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Servicio</td>
                        <td>
                            <div class="border">
                                {{$model->Servicio}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Norma</td>
                        <td>
                            <div class="border">
                                {{$model->Clave_norma}}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Parametros</td>
                        <td>
                            <div class="border">
                                @foreach ($parametros as $item)
                                    {{$item->Parametro}}
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%">Parametros especiales</td>
                        <td>
                            <div class="border">
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
                        <td style="width: 30%">Folio de muestreo</td>
                        <td>
                            <div class="border">
                                {{$model->Observacion}}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</body>
</html> 