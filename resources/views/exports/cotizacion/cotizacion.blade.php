<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">
    <title>Cotización: {{$model->Folio}}</title>
</head> 
<body>
    <div class="container"> 
        <div class="row">
            <div class="col-md-12" style="display: flex;">
                <img src="https://dev.sistemaacama.com.mx//storage/Logo_sin_fondo.png" style="width: 100;">
                <center><div style="font-size: 11px" class="verdeClaro">LABORATORIO DE ANÁLISIS DE CALIDAD <br> DEL AGUA Y MEDIOS AMBIENTALES S.A DE C.V</div></center>
            </div>
            <div class="col-md-12">
                <p align="right">FOLIO COTIZACIÓN: {{$model->Folio}}</p>
            </div>
            <div class="col-md-12" style="font-size: 11px;">
                {{$model->Nombre}} <br> {{$model->Direccion}}
            </div><br>
            <div class="col-md-12" style="font-weight: bold;">
                TELF: {{$model->Telefono}} <br>
                EMAIL: {{$model->Correo}} <br>
                ATN: {{$model->Atencion}} 
                <p align="right"></p>
            </div>
            <div class="col-md-12">
                ME PERMITO SOMETER A SU AMABLE CONSIDERACIÓN LA SIGUIENTE COTIZACIÓN DEL SERVICIO DE MUESTREO Y ANALISIS DE AGUA DE ACUERDO A:
            </div>
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td style="width: 30px">SERVICIO</td>
                        <td>NÚM NORMAS</td>
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
</body>
{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script> --}}
</html> 