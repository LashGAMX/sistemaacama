<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\CampoCompuesto;
use App\Models\CampoGenerales;
use App\Models\CodigoParametros;
use App\Models\ConductividadMuestra;
use App\Models\PhMuestra;
use App\Models\ProcedimientoAnalisis;
use App\Models\ProcesoAnalisis;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use App\Models\SolicitudParametro;
use App\Models\SolicitudPuntos;
use App\Models\SucursalCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class IngresarController extends Controller
{

    public function index()
    {
        $idUser = Auth::user()->id;

        $model = DB::table('ViewSolicitud')->get();
        return view('ingresar.ingresar', compact('idUser', 'model'));
    }

    public function recepcion()
    {
        $idUser = Auth::user()->id;

        $model = DB::table('ViewSolicitud')->get();
        return view('ingresar.recepcion', compact('idUser', 'model'));
    }

    public function buscarFolio(Request $request)
    {
        $tempCli = DB::table('ViewSolicitud2')->where('Folio_servicio', $request->folioSol)->get();
        if ($tempCli->count()) {
            $cliente = DB::table('ViewSolicitud2')->where('Folio_servicio', $request->folioSol)->first();
            $model = DB::table('ViewSolicitud2')->where('Hijo', $cliente->Id_solicitud)->get();
            $proceso = ProcesoAnalisis::where('Id_solicitud',$cliente->Id_solicitud)->get();
            $std = true;
            if ($proceso->count()) {
                $std = true;
            }
        }else{
            $cliente = "";
        }

        $array = array(
            'cliente' => $cliente,
            'sw' => $tempCli->count(),
        );
        return response()->json($array);
    }
    public function getPuntoMuestreo(Request $res)
    {
        $model = SolicitudPuntos::where('Id_solPadre',$res->id)->get();
        $cloruro = array();
        $conductividad = array();
        $temp = 0;
        $aux = 0;
        foreach ($model as $item) {
            $condModel = ConductividadMuestra::where('Id_solicitud',$item->Id_solicitud)->where('Activo',1)->get();
            if ($condModel->count()) {
                $aux = 0;
                $temp = 0;
                foreach ($condModel as $item2) {
                    $temp = $temp + $item2->Promedio;
                    $aux++;
                }
                $temp = $temp / $aux;
                array_push($conductividad,$temp);
            }else{
                array_push($conductividad,'');
            }

            $campoModel = CampoCompuesto::where('Id_solicitud',$item->Id_solicitud)->get();
            if ($campoModel->count()) {
                switch ($campoModel[0]->Cloruros) {
                    case $campoModel[0]->Cloruros < 1000:
                        array_push($cloruro,1);
                        break;
                    case $campoModel[0]->Cloruros >= 1000 && $campoModel[0]->Cloruros < 1500:
                        array_push($cloruro,2);
                        break;
                    case $campoModel[0]->Cloruros >= 1500 && $campoModel[0]->Cloruros < 2000:
                        array_push($cloruro,3);
                        break;
                    case $campoModel[0]->Cloruros >= 2000 && $campoModel[0]->Cloruros < 3000:
                        array_push($cloruro,4);
                        break;
                    case $campoModel[0]->Cloruros >= 3000:
                        array_push($cloruro,5);
                        break;
                    default:
                        # code...
                        break;
                }
            }else{
                array_push($cloruro,'');
            }
        }
        $data = array(
            'cloruro' => $cloruro,
            'conductividad' => $conductividad,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getCodigoRecepcion(Request $res)
    {
        $model = CodigoParametros::where('Id_solicitud', $res->idSol)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDataPuntoMuestreo(Request $res)
    {
        $sol = Solicitud::where('Id_solicitud', $res->idSol)->first();
        $model = PhMuestra::where('Id_solicitud', $res->idSol)->orderBy('Id_ph', 'DESC')->first();
        $fecha2 = new \Carbon\Carbon($model->Fecha);
        $procedencia = SucursalCliente::where('Id_sucursal',$sol->Id_sucursal)->first();
        
        $data = array(
            'procedencia' => $procedencia,
            'fecha2' => $fecha2->addMinutes(30)->format('Y-m-d H:i:s'),
            'sol' => $sol,
            'model' => $model,
        );
        return response()->json($data);
    }

    public function setGenFolio(Request $res)
    {
        $sw = false;
        $model = DB::table('ViewSolicitud2')->where('Hijo', $res->id)->get();

        foreach ($model as $item) {
            $swCodigo = CodigoParametros::where('Id_solicitud', $item->Id_solicitud)->get();
            if ($swCodigo->count()) {
            } else {
                $canceladoAux = array();
                if ($item->Id_servicio != 3) {
                    for ($i=0; $i <$item->Num_tomas ; $i++) { 
                        array_push($canceladoAux,0);
                    }
                } else {
                    for ($i=0; $i <$item->Num_tomas ; $i++) { 
                        array_push($canceladoAux,0);
                    }
                }
                
            
                $parametros = SolicitudParametro::where('Id_solicitud',$item->Id_solicitud)->get();
                foreach ($parametros as $item2) {
                    switch ($item2->Id_parametro) {
                        case 13: // G&A
                                for ($i = 0; $i < $item->Num_tomas; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $item->Id_solicitud,
                                        'Id_parametro' => $item2->Id_parametro,
                                        'Codigo' => $item->Folio_servicio . "-G-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Reporte' => $item2->Reporte,
                                        'Cancelado' => 0,
                                    ]);
                                }
                            break;
                        default:
                            CodigoParametros::create([
                                'Id_solicitud' => $item->Id_solicitud,
                                'Id_parametro' => $item2->Id_parametro,
                                'Codigo' => $item->Folio_servicio,
                                'Num_muestra' => 1,
                                'Asignado' => 0,
                                'Analizo' => 1,
                                'Reporte' => $item2->Reporte,
                                'Cancelado' => 0,
                            ]);
                            break;
                    }
                }
            }
        }
        
        
        

            foreach ($model as $value) {
                # code...
                $sw = false;
                $cont = 0;
                $swCodigo = CodigoParametros::where('Id_solicitud', $value->Id_solicitud)->get();
                $solParam = DB::table('ViewSolicitudParametros')->where('Id_solicitud', $value->Id_solicitud)->get();

                if ($swCodigo->count()) {
                    $sw = true;
                } else {
                    foreach ($solParam as $item) {

                        switch ($item->Id_parametro) {
                            case 13:
                                // G&A
                                for ($i = 0; $i < $phMuestra->count(); $i++) {
                                    if ($phMuestra[$i]->Activo == 1) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $value->Id_solicitud,
                                            'Id_parametro' => $item->Id_parametro,
                                            'Codigo' => $value->Folio_servicio . "-G-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => $item->Reporte,
                                        ]);
                                    }
                                }
                                break;
                            case 12:
                            case 78:
                                // Coliformes
                                for ($i = 0; $i < $phMuestra->count(); $i++) {
                                    if ($phMuestra[$i]->Activo == 1) {
                                        CodigoParametros::create([
                                            'Id_solicitud' => $value->Id_solicitud,
                                            'Id_parametro' => $item->Id_parametro,
                                            'Codigo' => $value->Folio_servicio . "-C-" . ($i + 1) . "",
                                            'Num_muestra' => $i + 1,
                                            'Asignado' => 0,
                                            'Analizo' => 1,
                                            'Reporte' => $item->Reporte,
                                        ]);
                                    }
                                }
                                break;
                            case 5:
                                // DBO
                                for ($i = 0; $i < 3; $i++) {
                                    CodigoParametros::create([
                                        'Id_solicitud' => $value->Id_solicitud,
                                        'Id_parametro' => $item->Id_parametro,
                                        'Codigo' => $value->Folio_servicio . "-D-" . ($i + 1) . "",
                                        'Num_muestra' => $i + 1,
                                        'Asignado' => 0,
                                        'Analizo' => 1,
                                        'Cadena' => 0, 
                                        'Reporte' => $item->Reporte,
                                    ]);
                                }
                                break;
                            case 6:
                                // DQO
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => $item->Reporte,
                                ]);

                                break;
                            case 152:
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => $item->Reporte,
                                ]);
                                break;
                            case 35:
                                //E.Coli
                                if ($promConduc < 3500) {
                                    for ($i = 0; $i < $phMuestra->count(); $i++) {
                                        if ($phMuestra[$i]->Activo == 1) {
                                            CodigoParametros::create([
                                                'Id_solicitud' => $value->Id_solicitud,
                                                'Id_parametro' => $item->Id_parametro,
                                                'Codigo' => $value->Folio_servicio . "-EC-" . ($i + 1) . "",
                                                'Num_muestra' => $i + 1,
                                                'Asignado' => 0,
                                                'Analizo' => 1,
                                                'Reporte' => $item->Reporte,
                                            ]);
                                        }
                                    }
                                }
                                break;
                            case 253:
                                //Enterococos
                                if ($promConduc >= 3500) {
                                    for ($i = 0; $i < $phMuestra->count(); $i++) {
                                        if ($phMuestra[$i]->Activo == 1) {
                                            CodigoParametros::create([
                                                'Id_solicitud' => $value->Id_solicitud,
                                                'Id_parametro' => $item->Id_parametro,
                                                'Codigo' => $value->Folio_servicio . "-EF-" . ($i + 1) . "",
                                                'Num_muestra' => $i + 1,
                                                'Asignado' => 0,
                                                'Analizo' => 1,
                                                'Reporte' => $item->Reporte,
                                            ]);
                                        }
                                    }
                                }
                                break;
                            default:
                                CodigoParametros::create([
                                    'Id_solicitud' => $value->Id_solicitud,
                                    'Id_parametro' => $item->Id_parametro,
                                    'Codigo' => $value->Folio_servicio,
                                    'Num_muestra' => 1,
                                    'Asignado' => 0,
                                    'Analizo' => 1,
                                    'Reporte' => $item->Reporte,
                                ]);
                                break;
                        }
                    }
                }
            }
        } else {
            $sw = false;
        }





        $data = array(
            'sw' => $sw,
        );
        return response()->json($data);
    }
    //pp 
    public function fechaFinSiralab(Request $request)
    {
        $siralab = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal', $request->sucursal)->first();
        return response()->json(compact('siralab'));
    }

    public function setIngresar(Request $res)
    {
        $model = DB::table('ViewSolicitud2')->where('Id_solicitud',$res->id)->get();
        // $model = ProcesoAnalisis::where('Id_solicitud', $request->idSol)->get();
        // $seguimiento = SeguimientoAnalisis::where('Id_servicio', $request->idSol)->first();
        // $muestra2 = DB::table('ViewSolicitud')->where('Hijo', $request->idSol)->get();
        // $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $request->idSol)->first();
        // $muestra = PhMuestra::where('Id_solicitud', $muestra2[0]->Id_solicitud)->first();
        // $sw = false;
        // $fecha_muestreo = new Carbon();
        // $fecha_ingreso = new Carbon();
        // if ($solModel->Id_servicio == 3) {
        //     $fecha_muestreo->toDateString(date('d/m/y'));
        // } else {
        //     $fecha_muestreo->toDateString(@$muestra->Fecha);
        // }
    
        // $fecha_ingreso->toDateString($request->horaRecepcion);
        // $date1 = new DateTime($request->horaRecepcion);
        // $date2 = new DateTime($muestra->Fecha);
        // $diff = $date1->diff($date2);
        // $valProce = ProcesoAnalisis::where('Id_solicitud',$request->idSol)->get();
        // if ($date1 >= $date2) {
        //     if($diff->days > 2){
        //         $msg = "La fecha de recepcion sobrepasa el limite lo permitido";
        //     }else{
        //         $solModel = Solicitud::where('Hijo', $request->idSol)->get();
        
        //         ProcesoAnalisis::create([
        //             'Id_solicitud' => $request->idSol,
        //             'Folio' => $request->folio,
        //             'Descarga' => $request->descarga,
        //             'Cliente' => $request->cliente,
        //             'Empresa' => $request->empresa,
        //             'Ingreso' => 1,
        //             'Hora_recepcion' => $request->horaRecepcion,
        //             'Hora_entrada' => $request->horaEntrada,
        //             'Liberado' => 0,
        //             'Id_user_c' => Auth::user()->id,
        //         ]);
        //         foreach ($solModel as $item) {
        //             ProcesoAnalisis::create([
        //                 'Id_solicitud' => $item->Id_solicitud,
        //                 'Folio' => $item->Folio_servicio,
        //                 'Descarga' => $request->descarga,
        //                 'Cliente' => $request->cliente,
        //                 'Empresa' => $request->empresa,
        //                 'Ingreso' => 1,
        //                 'Hora_recepcion' => $request->horaRecepcion,
        //                 'Hora_entrada' => $request->horaEntrada,
        //                 'Liberado' => 0,
        //                 'Id_user_c' => Auth::user()->id,
        //             ]);
        //         }
        //         $sw = true;
        //         $msg = "Muestra ingresada";
        //     }
        // } else {
        //     $msg = "La fecha de recepción es menor a la fecha de muestreo?";
        // }
        
        // $array = array(
        //     'valProce' => $valProce,
        //     'fecha' => $diff->days,
        //     'msg' => $msg,
        //     'sw' => $sw,
        //     'model' => $model,
        // );
        // return response()->json($array);
    }

    //Método para obtener la fecha de conformación
    public function fechaConformacion(Request $request)
    {
        $fechaC = DB::table('ph_muestra')->where('Id_solicitud', $request->idSolicitud)->get();

        return response()->json(compact('fechaC'));
    }

    //Método para obtener la procedencia con previa cotización
    public function procedencia(Request $request)
    {
        $cotizacion_muestreos = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $request->idCotizacion)->first();

        if ($cotizacion_muestreos !== null) {
            $estado = DB::table('estados')->where('Id_estado', $cotizacion_muestreos->Estado)->first();
        }

        return response()->json(compact('estado'));
    }


    //----------------------------------MÓDULO GENERAR-------------------------------------
    public function genera2()
    {
        $idUser = Auth::user()->id;
        $model = DB::table('ViewSolicitud')->get();

        return view('ingresar.solicitud', compact('idUser', 'model'));
    }

    public function buscadorGen(Request $request)
    {
        $model = ProcesoAnalisis::where('Folio', $request->busquedaIn)->first();

        return response()->json(compact('model'));
    }
}
