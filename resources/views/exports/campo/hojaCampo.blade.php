<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/css/pdf/hojaCampo.css')}}">
 
    <title>Hoja de Campo </title>
</head>
<body style="font-size: 9px"> 
    <div class="container" id="pag">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless table-sm colorBorde">
                    <tr>
                        <td class="negrita"><center><h6>HOJA DE CAMPO Y CADENA DE CUSTODIA EXTERNA</h6></center></td>   
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                <div>                
                    <div width="50%" style="float: left">
                        1. DATOS GENERALES
                    </div>
                    <div class="justifyRight" width="50%" style="float: left;">
                        {PAGENO} / {nbpg}
                    </div>                    
                </div>                                
            </div>
           
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}" width="100%">
                    <tr>
                        <td class="bordesTabla">NUM. DE MUESTRA</td>
                        <td class="negrita bordesTablaSupInfDer">{{@$model->Folio_servicio}}</td>
                        <td class="bordesTablaSupInfDer">No DE ORDEN</td>
                        <td class="bordesTablaSupInfDer justifyCenter negrita">{{@$folioPadre->Folio_servicio}}</td>
                        <td class="bordesTablaSupInfDer">FECHA DE MUESTREO</td>
                        <td class="bordesTablaSupInfDer justifyCenter negrita">{{\Carbon\Carbon::parse(@$model->Fecha_muestreo)->format('d/m/Y')}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">NORMA APLICABLE</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="3">{{@$model->Clave_norma}}</td>
                        <td class="bordesTablaInfIzqDer">MATRIZ</td>
                        <td class="justifyCenter negrita bordesTablaInfIzqDer">{{@$model->Descarga}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">EMPRESA</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">{{@$model->Empresa_suc}}</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">DIRECCION</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">
                            {{@$direccion->Direccion}}
                        </td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">PUNTO DE MUESTREO</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">
                           {{@$puntoMuestreo->Punto}}
                        </td>
                    </tr>
                    @if ($campoGeneral->Latitud != NULL)
                    <tr>
                        <td class="bordesTablaInfIzqDer">COORDENADAS</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">
                         Latitud: {{$campoGeneral->Latitud}}  Longitud: {{$campoGeneral->Longitud}}   Altitud: {{$campoGeneral->Altitud}}  
                        </td>
                    </tr>
                    @endif
                    {{-- <tr>
                        <td class="bordesTablaInfIzqDer">EQUIPOS </td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">
                         Equipo PC-100 : {{@$equipo1->Modelo}}  |  Equipo HANNA {{@$equipo2->Modelo}}
                        </td>
                    </tr> --}}
                </table>
            </div> 


            <div class="col-12 negrita">
                2. RECIPIENTES UTILIZADOS
            </div>

            
            <div class="col-md-12">
                <table class="{{-- table --}} {{-- table-bordered border-dark --}} table-sm {{-- colorBorde --}}" width="100%">
                    <thead>
                        <tr>
                            <td class="negrita justifyCenter bordesTabla">#</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">ANALISIS</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">PARAMETRO</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">ENVASE</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">VOLUMEN</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">PRESERVACION</td>
                            <td class="negrita justifyCenter bordesTablaSupInfDer">SI/NO</td>
                        </tr>
                    </thead>

                    <tbody>     
                        @foreach ($areaModel as $item)
                            @php
                                $cont = 0;
                                $mod = DB::table('viewenvaseparametro')->where('Id_analisis',$item->Id_area)->get();
                            @endphp
                            @foreach ($mod as $item2)
                                @php
                                    $pa = DB::table('solicitud_parametros')->where('Id_subnorma',$item2->Id_parametro)->where('Id_solicitud',$model->Id_solicitud)->get();
                                @endphp
                                @if ($pa->count())
                                    @if ($cont == 0)
                                          <tr class="bordesTablaSup">
                                            @if ($item2->Id_area == 2 || $item2->Id_area == 7 || $item2->Id_area == 16)
                                                <td class="justifyCenter  fontSize7">{{$model->Num_tomas}}</td>
                                            @else
                                                <td class="justifyCenter  fontSize7">1</td>  
                                            @endif
                                            <td class="justifyCenter  fontSize7">{{$item2->Area}}</td>
                                            <td class="justifyCenter  fontSize7">{{$item2->Parametro}}</td>
                                            <td class=" fontSize7">{{$item2->Nombre}} {{$item2->Volumen}} {{@$item2->UniEnv}}</td>
                                            <td class="justifyCenter  fontSize7">{{$item2->Volumen}} {{@$item2->UniEnv}}</td>                                    
                                            <td class=" fontSize7">{{$item2->Preservacion}}</td>
                                            <td class="justifyCenter  fontSize7">SI</td>                                    
                                        </tr>
                                        @php $cont++; @endphp
                                    @else
                                        <tr>
                                            <td class="justifyCenter  fontSize7"></td>
                                            <td class="justifyCenter  fontSize7"></td>
                                            <td class="justifyCenter  fontSize7">{{$item2->Parametro}}</td>
                                            <td class=" fontSize7">{{$item2->Nombre}} {{$item2->Volumen}} {{@$item2->UniEnv}}</td>
                                            <td class="justifyCenter  fontSize7">{{$item2->Volumen}} {{@$item2->UniEnv}}</td>                                    
                                            <td class=" fontSize7">{{$item2->Preservacion}}</td>
                                            <td class="justifyCenter  fontSize7">SI</td>                                    
                                        </tr>
                                    @endif
                                @endif 
                            @endforeach
                        @endforeach    
                   
                    </tbody>
                    
                </table>
            </div> 

            <div class="col-12">
                Todas las muestras se conservan en hielo.
            </div>
            <div class="col-12 negrita">
                3. DATOS DE CAMPO
            </div>

            <div class="col-12 negrita">
                4. ENTREGA A LABORATORIO
            </div>

        </div>
    </div>
</body>
</html> 