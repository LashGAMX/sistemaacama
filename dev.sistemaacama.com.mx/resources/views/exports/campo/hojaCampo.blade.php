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
<body style="font-size: 10px"> 
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
                        <td class="bordesTablaSupInfDer justifyCenter negrita">{{@$numOrden->Folio_servicio}}</td>
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
                            @if ($model->Siralab != 1)
                                {{@$direccion->Direccion}}
                            @else
                                {{@$direccion->Calle}} {{@$direccion->Num_exterior}} {{@$direccion->Num_interior}} {{@$direccion->Colonia}} {{@$direccion->NomMunicipio}} {{@$direccion->Estado}} CP: {{@$direccion->CP}}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer">PUNTO DE MUESTREO</td>
                        <td class="negrita bordesTablaInfIzqDer" colspan="5">
                            {{-- @for ($i = 0; $i < @$puntos; $i++) --}}
                                @if (@$model->Siralab == 1)
                                    {{@$puntoMuestreo[0]->Punto}} (anexo {{@$puntoMuestreo[0]->Anexo}})<br>  
                                @else
                                    {{@$puntoMuestreo[0]->Punto_muestreo}} <br>
                                @endif    
                            
                            {{-- @endfor --}}
                        </td>
                    </tr>
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
                        
                        @foreach (@$areaModel as $item)
                            @php
                            $cont = 0;
                            $mod = DB::table('ViewEnvaseParametroSol')->where('Id_area',$item->Id_area)->where('Id_solicitud',$model->Id_solicitud)->orderBy('Parametro','asc')->get();       
                            @endphp                                  
                                @if ($mod->count())
                                   @foreach ($mod as $item2)
                                    @if ($cont == 0) 
                                    <tr class="bordesTablaSup">
                                        @if ($item2->Id_area == 2 || $item2->Id_area == 7 || $item2->Id_area == 16)
                                            <td class="justifyCenter  fontSize7">{{$model->Num_tomas}}</td>
                                        @else
                                            <td class="justifyCenter  fontSize7">1</td>
                                        @endif
                                        <td class="justifyCenter  fontSize7">{{$item2->Area}}</td>
                                        <td class="justifyCenter  fontSize7">{{$item2->Parametro}}</td>
                                        <td class=" fontSize7">{{$item2->Nombre}} {{$item2->Volumen}} {{$item2->UniEnv}}</td>
                                        <td class="justifyCenter  fontSize7">{{$item2->Volumen}} {{$item2->UniEnv}}</td>                                    
                                        <td class=" fontSize7">{{$item2->Preservacion}}</td>
                                        <td class="justifyCenter  fontSize7">SI</td>                                    
                                    </tr>
                                    @php $cont++; @endphp
                                    @else
                                    <tr>
                                        <td class="justifyCenter  fontSize7"></td>
                                        <td class="justifyCenter  fontSize7"></td>
                                        <td class="justifyCenter  fontSize7">{{$item2->Parametro}}</td>
                                        <td class=" fontSize7">{{$item2->Nombre}} {{$item2->Volumen}} {{$item2->UniEnv}}</td>
                                        <td class="justifyCenter  fontSize7">{{$item2->Volumen}} {{$item2->UniEnv}}</td>                                    
                                        <td class=" fontSize7">{{$item2->Preservacion}}</td>
                                        <td class="justifyCenter  fontSize7">SI</td>                                    
                                    </tr>
                                    @endif
                                   @endforeach
                            @endif
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
            <div class="col-md-12">
                <table class="{{-- table table-borderless --}} table-sm {{-- colorBorde --}}">
                    <tr>
                      <td class="bordesTabla justifyCenter fontSize11">No. DE MUESTRAS</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">FECHA Y HORA DE MUESTREO</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">GASTO (L/s)</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">MAT. FLOT. (AUS/PR)</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">pH (Unidad)</td>                      
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">TEMP AMB C°</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">TEMP AGUA C°</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">OLOR (SI/NO)</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">COLOR</td>
                      <td class="bordesTablaSupInfDer justifyCenter fontSize11">COND (μs/cm)</td>
                    </tr>
                    @for ($i = 0; $i < @$model->Num_tomas; $i++)
                        <tr>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">{{$i + 1}}</td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">{{\Carbon\Carbon::parse(@$phMuestra[$i]->Fecha)->format('d/m/Y H:i')}}</td>
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">

                            @if (@$gastoMuestra[$i]->Activo == 1)
                                @if (@$gastoMuestra[$i]->Promedio == "" || @$gastoMuestra[$i]->Promedio == null)
                                    ---
                                @else 
                                    {{@$gastoMuestra[$i]->Promedio}}
                                @endif  
                            @else
                                ---
                            @endif    
                                
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">
                                @if (@$phMuestra[$i]->Activo == 1)
                                    @if ($swMateria->count())
                                        {{@$phMuestra[$i]->Materia}}
                                    @else
                                        ---
                                    @endif
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">                                
                                @if (@$phMuestra[$i]->Activo == 1)
                                    @if (@$phMuestra[$i]->Promedio == "" || @$phMuestra[$i]->Promedio == null)
                                            ---
                                        @else
                                            {{@$phMuestra[$i]->Promedio}}
                                        @endif
                                    
                                @else
                                    ---
                                @endif
                            </td>                            
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">
                                @if (@$phMuestra[$i]->Activo == 1)                           
                                    @if (@$tempAmbiente[$i]->Temperatura1 == "" || @$tempAmbiente[$i]->Temperatura1 == null)
                                                ---
                                            @else
                                                @php
                                                    echo number_format(@$tempAmbiente[$i]->Temperatura1, 0, ".", ",");
                                                @endphp
                                            @endif
                                   
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">
                                @if (@$phMuestra[$i]->Activo == 1)                            
                                    @if (@$tempMuestra[$i]->Promedio == "" || @$tempMuestra[$i]->Promedio == null)
                                            ---
                                        @else
                                            @php
                                                echo number_format(@$tempMuestra[$i]->Promedio, 0, ".", ",");
                                            @endphp
                                        @endif
                                   
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">
                                @if (@$phMuestra[$i]->Activo == 1)
                                @if (@$phMuestra[$i]->Promedio == "" || @$phMuestra[$i]->Promedio == null)
                                        ---
                                    @else
                                    {{@$phMuestra[$i]->Olor}}
                                    @endif
                                    
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">
                                @if (@$phMuestra[$i]->Activo == 1)
                                @if (@$phMuestra[$i]->Promedio == "" || @$phMuestra[$i]->Promedio == null)
                                        ---
                                    @else
                                    {{@$phMuestra[$i]->Color}}
                                    @endif
                                    
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="bordesTablaInfIzqDer negrita justifyCenter fontSize13">
                                @if (@$phMuestra[$i]->Activo == 1)
                                    @if (@$conMuestra[$i]->Promedio == "" || @$conMuestra[$i]->Promedio == null)
                                        ---
                                    @else
                                        {{@$conMuestra[$i]->Promedio}}
                                    @endif
                                @else
                                    ---
                                @endif
                            </td>
                        </tr>
                    @endfor
                </table>
            </div>
            <div class="col-md-12">
                <table style="width: 100%">
                    <tr>
                        <td class="bordesTablaInfIzqDer justifyCenter" width="50%">RESPONSABLE DEL MUESTREO (NOMBRE Y FIRMA)</td>
                        <td class="bordesTablaInfDer justifyCenter" width="50%">SUPERVISIÓN DEL MUESTREO (NOMBRE Y FIRMA)</td>    
                    </tr>
                    <tr>
                        <td rowspan="2" class="bordesTablaInfIzqDer justifyCenter"><span class="negrita"><br>{{@$muestreador->name}}</span> <img style="width: auto; height: auto; max-width: 50px; max-height: 50px;" src="{{asset('public/storage/'.@$firmaRes->firma)}}"> <br></td>
                        <td rowspan="2" class="bordesTablaInfDer justifyCenter"><span class="negrita">------------</span> </td>
                    </tr>
                </table>
            </div>
            <div class="col-12 negrita">
                4. ENTREGA A LABORATORIO
            </div>
            <div class="col-md-12">
                <table style="width: 100%">
                    <tr>
                        <td class="bordesTabla justifyCenter" style="width: 50%">
                       
                            @if ($procesoAnalisis->count())
                                <img style="width: auto; height: auto; max-width: 50px; max-height: 50px;" src="{{asset('public/storage/'.@$firmaRecepcion->firma)}}">
                            @else
                                " "
                            @endif
                        </td>
                      <td class="bordesTabla justifyCenter" style="width: 50%"><img style="width: auto; height: auto; max-width: 50px; max-height: 50px;" src="{{asset('public/storage/'.@$firmaRes->firma)}}"></td>                      
                    </tr>
                    <tr>
                        <td class="bordesTablaInfIzqDer justifyCenter" style="width: 50%">RECEPCION EN EL LABORATORIO</td>
                        <td class="bordesTablaSupInfDer justifyCenter" style="width: 50%">ENTREGA LA(S) MUESTRA(S)</td>
                    </tr>

                    <tr>
                        <td class="bordesTablaInfIzqDer justifyCenter" style="width: 50%">{{@$firmaRecepcion->name}} <br> <span class="fontSize7">Nombre y Firma</span></td>
                        <td class="bordesTablaSupInfDer justifyCenter" style="width: 50%">{{@$muestreador->name}} <br> <span class="fontSize7">Nombre y Firma</span></td>
                    </tr>

             
                    @if (@$model->Num_tomas > 1)
                        <tr>
                            <td class="bordesTablaInfIzqDer" style="width: 50%">Fecha y hora de recepción en Lab: 
                            <!-- {{\Carbon\Carbon::parse(@$recepcion->created_at)}} -->
                            </td>
                            <td class="bordesTablaSupInfDer" style="width: 50%">
                                Fecha y hora de conformación de la muestra: 
                                <!-- {{\Carbon\Carbon::parse(@$phMuestra[@$model->Num_tomas - 1]->Fecha)->addMinutes(30)->format('d/m/Y H:i:s')}} -->
                            </td>
                        </tr> 
                            <tr>
                                <td class="bordesTablaInfIzq" colspan="2">
                                    <table style="width: 100%">
                                        <tr>
                                            <td class="bordesTablaInfIzq">
                                                pH MUESTRA COMPUESTA:                             
                                                @php
                                                    echo number_format(@$modelCompuesto->Ph_muestraComp, 2, ".", ",");
                                                @endphp
                                            </td>
                                            <td class="bordesTablaInf">VOLUMEN MUESTRA COMPUESTA: {{@$modelCompuesto->Volumen_calculado}} L</td>
                                            <td class="bordesTablaInfDer">TEMPERATURA MUESTRA COMPUESTA: {{@$modelCompuesto->Temp_muestraComp}} °C</td>
                                        </tr>
                                        
                                    </table>
                                </td>
                                    <tr>
                                        <td class="bordesTablaInfIzq" style="width: 100%" colspan="2">OBSERVACIONES: {{@$modelCompuesto->Observaciones}}</td>
                                    </tr>
                            </tr>
                        @else
                            <tr>
                                <td class="bordesTablaInfIzqDer" style="width: 100%" colspan="2">Fecha y hora de recepción en Lab: 
                                <!-- {{\Carbon\Carbon::parse(@$recepcion->created_at)->format('d-m-Y H:i')}} -->
                            </td>

                            </tr>
                            <tr>
                                <tr>
                                    <td class="bordesTablaInfIzqDer" style="width: 100%" colspan="2">OBSERVACIONES: {{@$modelCompuesto->Observaciones}}</td>
                                </tr>
                            </tr>
                        @endif
                </table>
          
            </div>
            
            <br><br>

            <div class="col-12 ">
                @php
                    /* $url = url()->current(); */
                    $url = "https://sistemaacama.com.mx/clientes/hojaCampo/".@$model->Id_solicitud;
                    $qr_code = "data:image/png;base64," . \DNS2D::getBarcodePNG((string) $url, "QRCODE");
                @endphp
                                                        
                {{-- <img style="width: 12%; height: 12%;" src="{{@$qr_code}}" alt="qrcode" /> <br> <span class="fontSize9 fontBold">&nbsp; QR Hoja Campo</span> --}}
            </div>
        </div>
    </div>
</body>
</html> 