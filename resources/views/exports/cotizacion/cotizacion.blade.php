<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- <link rel="stylesheet" href="'.asset('css/pdf/style.css').'"> --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pdf/style.css')}}">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> --}}
    {{-- <link rel="stylesheet" href="'.asset('css/pdf/style.css').'" media="mpdf"> --}}

    <title>Cotizacion {{$model->Folio}}</title> 
</head>
<body> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col align-self-end">
            <P align="right">FOLIO COTIZACIÓN: {{$model->Folio}}<p>
            </div>
        </div><br>
        <div class="row" style="display: block">
            <div class="col-12 negrita">
                {{$model->Nombre}}
            </div>
            <div class="col-12 negrita">
                {{$model->Direccion}}
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 negrita">
                {{$model->Telefono}}<br>
                {{$model->Correo}}<br>
                {{$model->Atencion}}<br>
            </div><br>
            <div class="col-md-12">
                <p>ME PERMITO SOMETER A SU AMABLE CONSIDEREACIÓN LA SIGUIENTE COTIZACIÓN DEL SERVICIO DE MUESTREO Y ANÁLISIS DE AGUA DE ACEURDO A:</p>
            </div><br>
            <div class="col-md-12">
                <table class="table table-borderless" style="border:none">
                    <tr>
                        <td>SERVICIO: </td>
                        <td class="negrita">{{$model->Servicio}}</td>
                        <td>NÚM NORMAS:</td>
                        <td class="negrita">1</td>
                        <td>PUNTOS MUESTREO:</td>
                        <td class="negrita">1</td>
                        <td>SERVICIOS:</td>
                        <td class="negrita">1</td>
                    </tr>
                </table>
                <table class="table table-borderless" style="border:none">
                <tr>
                    <td>TIPO MUESTRA: </td>
                    <td class="negrita">{{$model->Tipo_muestra}}</td>
                    <td>NORMA:</td>
                    <td class="negrita">{{$model->Clave_norma}}</td>
                </tr>
            </table>
            </div><br>
            <div class="col-md-12 negrita">
                <strong><p>PRODUCTOS Y SERVICIOS. AGUA Y HIELO PARA CONSUMO HUMANO, ENVASADOS A GRANEL. ESPESIFICACIONES SANITARIAS</p></strong>
            </div>
        </div>
        
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th>PARAMETRO</th>
                    <th>METODO DE PRUEBA</th>
                    <th><small>LIMITE DE CUANTIFICACIÓN DEL METODO</small></th>
                    <th>UNIDAD</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parametros as $item)
                <tr>
                    <td>{{$item->Parametro}} <sup>({{$item->Simbologia}})</sup></td>
                    <td>{{$item->Metodo_prueba}}</td>
                    <td>{{$item->Limite}}</td>
                    <td>{{$item->Unidad}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table" style="font-size: 9px;">
            <tr>
                <td>CANTIDAD SERVICIOS: </td>
                <td>1.00</td>
                <td>COSTO UNITARIO</td>
                <td>${{$model->Sub_total}}</td>
                <td>COSTO SIN IVA</td>
                <td>${{$model->Sub_total}}</td>
            </tr>
        </table>
        <div class="col-md-12">
            <p>OBSERVACIONES COTIZACIÓN</p>
            <p style="font-weight: bold;">{{$model->Observacion_cotizacion}}</p>
        </div><br>
        <div class="col-md-12">
           <table class="table">
                <tr>
                <td>SUBTOTAL</td>
                <td>{{$model->Sub_total}}</td>
                </tr>
            <tr>
                <td>IVA</td>
                <td>{{(($model->Sub_total * 16)/100)}}</td>
            </tr>
           </table>
        </div>
        <div class="col-md-12">
            
        </div>
    </div>
</body>
</html> 