<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/custodiaInterna/custodiaInterna.css')}}">
 
    <title>Solicitud </title>
</head>
<body> 
IEFJIEJ
    HOLA

    <div class="container" id="pag">
        <div class="row">            
            <div class="col-12 negrita">
                <div>                
                    <div>
                        1.-DATOS GENERALES
                    </div>
                    <div>
                        <table class="table-sm">
                            <tr>
                                <td class="bordesTabla">N° de Muestra</td>
                                <td class="negrita bordesTablaSupInfDer">{{-- {{$model->Folio_servicio}} --}}FOLIO</td>
                                <td class="bordesTablaSupInfDer">Tipo de Muestra</td>
                                <td class="bordesTablaSupInfDer justifyCenter negrita">{{-- {{@$numOrden->Folio_servicio}} --}}T. MUESTRA</td>
                                <td class="bordesTablaSupInfDer">Norma Aplicable: </td>
                                <td class="bordesTablaSupInfDer justifyCenter negrita">NORMA</td>
                            </tr>
                        </table>
                    </div>                    
                </div>                                
            </div>



            
            <div class="col-12 negrita">
                2. RECIPIENTES UTILIZADOS
            </div>
            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}">
                    <thead>
                        <tr>
                            <td class="negrita justifyCenter bordesTabla">ÁREA</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">NOMBRE DEL RESPONSABLE</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">RECIPIENTES RECIBIDOS</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">FECHA DE SALIDA DEL REFRIGERADOR P/ANALISIS</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">FECHA ENTRADA DEL REFRIGERADOR P/GUARDAR</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">FECHA SALIDA DEL REFRIGERADOR P/ELIMINAR</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">FECHA EMISION DE RESULTADOS</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">FIRMA</td>                    
                        </tr>
                    </thead>

                    <tbody>                                           
                        {{-- @for ($i = 0; $i < $envasesLength; $i++) --}}
                            <tr>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Id_env}} --}}AREA</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Area}} --}}RESPONSABLE</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Parametro}} --}}RECIPIENTES</td>
                                <td class="bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Nombre}} --}}SALIDA ANALISIS</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Volumen}} --}} ENTRADA GUARDAR</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Unidad}} --}} SALIDA ELIMINAR</td>
                                <td class="bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Preservacion}} --}}EMISION RESULTADO</td>
                                <td class="justifyCenter bordesTablaInfIzqDer">{{-- {{@$envases[$i]->Preservacion}} --}}FIRMA</td>                                
                            </tr>
                        {{-- @endfor --}}
                    </tbody>
                    
                </table>
            </div>


            <div class="col-12 negrita">
                3. RESULTADOS
            </div>
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}">
                    <tr>
                      <td class="bordesTabla justifyCenter">Parametro</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Resultado</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Parametro</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Resultado</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Parametro</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Resultado</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Parametro</td>
                      <td class="bordesTablaSupInfDer justifyCenter">Resultado</td>                      
                    </tr>
                    {{-- @for ($i = 0; $i < $model->Num_tomas; $i++) --}}
                        <tr>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{-- {{$i + 1}} --}}PARAMETRO</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{-- {{\Carbon\Carbon::parse(@$phMuestra[$i]->Fecha)->format('d/m/Y')}} --}}RESULTADO</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">PARAMETRO{{-- {{$gastoMuestra[$i]->Promedio}} --}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{-- {{$phMuestra[$i]->Materia}} --}}RESULTADO</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">PARAMETRO{{-- {{$phMuestra[$i]->Promedio}} --}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{-- {{$punto->Punto_muestreo}} --}}RESULTADO</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">PARAMETRO{{-- {{$tempMuestra[$i]->Promedio}} --}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter">{{-- {{$tempMuestra[$i]->Promedio}} --}}RESULTADO</td>                            
                        </tr>
                    {{-- @endfor --}}
                </table>
            </div>

            


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