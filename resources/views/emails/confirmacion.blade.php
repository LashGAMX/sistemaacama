<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmacion Acama</title>
    <style type="text/css">
        .title{
            text-align:center;
            font-family: sans-serif;
            padding: 20px 0 0;

        }
        .subtitle{
            font-family: sans-serif;
            padding: 10px 0 0;
        }
        .texto{
            text-align: center;
            font-family: sans-serif;
            padding: 30px 0 0 0;
        }
        .logo{
            display: table;
            margin: 20px auto 0;
            max-width: 40%;
            max-height: 40%;
        }
        table{
            border-spacing: 0;
        }
        td {
            padding: 0;
        } 
        
        img{
            border: 0;
            min-width: 100px;
        }
        .main{
            background-color: white;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif
        }
        .grapper{
            width: 100%;
            table-layout: fixed;
            background-color: beige;
            
        }
       button {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 5px;
  background: #d6b821;
  font-family: "Montserrat", sans-serif;
  box-shadow: 0px 6px 24px 0px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  cursor: pointer;
  border: none;
  margin: 30px 0 0;
}

button:after {
  content: " ";
  width: 0%;
  height: 100%;
  background: #22443e; 
  position: absolute;
  transition: all 0.4s ease-in-out;
  right: 0;
}

button:hover::after {
  right: auto;
  left: 0;
  width: 100%;
}

button span {
  text-align: center;
  text-decoration: none;
  width: 100%;
  padding: 18px 25px;
  color: #fff;
  font-size: 1.125em;
  font-weight: 700;
  letter-spacing: 0.3em;
  z-index: 20;
  transition: all 0.3s ease-in-out;
}

button:hover span {
  color: #ffffff;
  animation: scaleUp 0.3s ease-in-out;
}

@keyframes scaleUp {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(0.95);
  }

  100% {
    transform: scale(1);
  }
}

    </style>
</head>
<body>
   <center class="wrapper">
        <table class="main" width="100%">
        <!--TOP BORDER -->
            <tr>
                <td height="8px" style="background-color: rgb(42, 168, 151)"></td>
            </tr>
        <!--LOGO -->
            <tr>
                <td>
                    <table width="100%">
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                    
                                                <img src="https://acama.com.mx/wp-content/uploads/2023/06/Acama_Imagotipo.png" 
                                                alt=""width="100px" height="100%" style="padding: 20px 0 0" class="logo">
                                            
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
       
        <!--title-->
        <tr>
            <td>
                <h1 class="title">CONFIRMACIÓN DE SERVICIO DE ANÁLISIS</h1>
            </td>
        </tr>
         <!--DATOS-->
         <tr>
                <td class="texto">
                Estimado <b>{{$solicitud->Nombres}}</b> tu servicio de analisis y muestreo ha sido cofirmado, a continuacion se muestran los datos de tu servicio para su confirmacion
                </td>
            </tr>
        <tr>
            <td>
                <br>
                <table>
                    <tr>
                        <td class="subtitle">Nombre de la empres: </td>
                        <td class="subtitle"><b> {{$solicitud->Empresa}}</b></td>
                    </tr>
                    <tr>
                        <td class="subtitle">Servicio a realizar: </td>
                        <td class="subtitle"><b> {{$solicitud->Servicio}}, {{$solicitud->Clave_norma}}</b></td>
                    </tr>
                    <tr>
                        <td class="subtitle">Fecha del servicio: </td>
                        <td class="subtitle"><b>{{$solicitud->Fecha_muestreo}}</b></td>
                    </tr>
                    <tr>
                        <td class="subtitle">Nombre de contacto: </td>
                        <td class="subtitle"><b>{{$contacto->Telefono}}</b></td>
                    </tr>
                    <tr>
                        <td class="subtitle">Numero de contacto: </td>
                        <td class="subtitle"><b> {{$solicitud->Servicio}}</b></td>
                    </tr>
                    <tr>
                        <td class="subtitle">Nombre punto de muestreo: </td>
                        <td class="subtitle"><b> {{$punto->Punto}}</b></td>
                    </tr>
                    <tr>
                        <td class="subtitle">Numero de anexo de descarga: </td>
                        <td class="subtitle"><b> N/A</b></td>
                    </tr>
                </table>
            </td> 
        </tr>
        <tr>
                <td class="texto">
                Condicion especial para el ingreso a instalaciones o toma de muestra: <b>{{$sol->Observacion}}</b>
                </td>
            </tr>
        <tr>
            <td class="texto">
             NOTA: LOS DATOS DE LA EMPRESA (NOMBRE, DIRECCION) SE VERAN REFLEJADOS EN SU INFORME DE RESULTADOS, SI LA INFORMACION ES CORRECTA, FAVOR DE CONFIRMAR, EN CASO DE SER DIFERENTES, RESPONDER ESTE CORREO CON LA INFROMACION CORRESPONDIENTE.
            </td>
        </tr>
       
            
         <!--FOOTER-->
       
                    </table>
                </td>
            </tr>
        </table>
        <br>
                <a href="https://acama.com.mx/confirmacion-2/" style="padding: 20px 0 0">Click Para Confirmar</a>
                <button>
                <a href="https://acama.com.mx/confirmacion-2/">CONFIRMAR</a>
                </button>

                <button onclick="location.href='https://acama.com.mx/confirmacion-2/'">
                    <span>CONFIRMAR</span>
                </button>
            
                <img src="https://acama.com.mx/wp-content/uploads/2020/05/fondo-formcontacto.png" alt="" width="600px" height="90" style="padding: 20px 0 0">
   </center>
    <!-- <h1 class="title">CONFIRMACIÓN DE SERVICIO DE ANÁLISIS</h1>
    <br>
    <p>Estimado <b>{{$solicitud->Nombres}}</b> tu servicio de analisis y muestreo ha sido cofirmado, a continuacion se muestran los datos de tu servisio para su confirmacion</p>
    <br>
    <p>Nombre de la empres: <b>{{$solicitud->Empresa}}</b></p>
    <p>Servicio a realizar: <b>{{$solicitud->Servicio}}</b></p>
    <p>Fecha de elaboracion del servicio: <b>{{$solicitud->Fecha_muestreo}}</b></p>
    <p>Nombre de contacto: <b>{{$contacto->Nombres}}</b></p>
    <p>Numero de contacto: <b>{{$contacto->Telefono}}</b></p>
    <p>Nombre del punto de muestreo: <b>{{$punto->Punto}}</b></p>
    <p>Numero de anexo de la descarga: <b>{{$solicitud->Empresa}}</b></p>
    <br>
    <p>Condicion especial para el ingreso a instalaciones o toma de muestra: <b>Se necesita presentar a las 8:00 am con equipo de seguridad.</b></p> -->

</body>
</html>