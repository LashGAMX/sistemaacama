<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\CodigoParametros;
use App\Models\PhMuestra;
use App\Models\ProcedimientoAnalisis;
use App\Models\ProcesoAnalisis;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $cliente = DB::table('ViewSolicitud')->where('Folio_servicio', $request->folioSol)->first();
        $model = DB::table('ViewSolicitud')->where('Hijo', $cliente->Id_solicitud)->get();
        $siralab = false;
        if ($cliente->Siralab == 1) {
            $puntos = DB::table('ViewPuntoMuestreoSolSir')->where('Id_solPadre', $cliente->Id_solicitud)->get();
            $siralab = true;
        } else {
            $puntos = DB::table('ViewPuntoGenSol')->where('Id_solPadre', $cliente->Id_solicitud)->get();
        }

        $array = array(
            'model' => $model,
            'cliente' => $cliente,
            'puntos' => $puntos,
            'siralab' => $siralab,
        );
        return response()->json($array);
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
        $muestreo = DB::table('ViewCotizacionMuestreo')->where('Id_cotizacion', $sol->Id_cotizacion)->first();
        $model = PhMuestra::where('Id_solicitud', $res->idSol)->orderBy('Id_ph', 'DESC')->first();

        $data = array(
            'sol' => $sol,
            'muestreo' => $muestreo,
            'model' => $model,
        );
        return response()->json($data);
    }
    //pp 
    public function fechaFinSiralab(Request $request)
    {
        $siralab = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal', $request->sucursal)->first();
        return response()->json(compact('siralab'));
    }

    public function setIngresar(Request $request)
    {
        $model = ProcesoAnalisis::where('Id_solicitud', $request->idSol)->get();
        $seguimiento = SeguimientoAnalisis::where('Id_servicio', $request->idSol)->first();
        $muestra2 = DB::table('ViewSolicitud')->where('Hijo', $request->idSol)->get();
        $solModel = DB::table('ViewSolicitud')->where('Id_solicitud', $request->idSol)->first();
        $muestra = PhMuestra::where('Id_solicitud', $muestra2[0]->Id_solicitud)->first();
        $sw = false;
        $fecha_muestreo = new Carbon();
        $fecha_ingreso = new Carbon();
        if ($solModel->Id_servicio == 3) {
            $fecha_muestreo->toDateString(date('d/m/y'));
        } else {
            $fecha_muestreo->toDateString(@$muestra->Fecha);
        }
    
        $fecha_ingreso->toDateString($request->horaRecepcion);

        $phVacio = true;
        $validacion = true;
        $solModel = Solicitud::where('Hijo', $request->idSol)->get();

        ProcesoAnalisis::create([
            'Id_solicitud' => $request->idSol,
            'Folio' => $request->folio,
            'Descarga' => $request->descarga,
            'Cliente' => $request->cliente,
            'Empresa' => $request->empresa,
            'Ingreso' => 1,
            'Hora_recepcion' => $request->horaRecepcion,
            'Hora_entrada' => $request->horaEntrada,
            'Liberado' => 0,
        ]);
        foreach ($solModel as $item) {
            ProcesoAnalisis::create([
                'Id_solicitud' => $item->Id_solicitud,
                'Folio' => $item->Folio_servicio,
                'Descarga' => $request->descarga,
                'Cliente' => $request->cliente,
                'Empresa' => $request->empresa,
                'Ingreso' => 1,
                'Hora_recepcion' => $request->horaRecepcion,
                'Hora_entrada' => $request->horaEntrada,
                'Liberado' => 0,
            ]);
        }
        $sw = true;

        $array = array(
            'sw' => $sw,
            'model' => $model,
            'validacion' => $validacion,
        );
        return response()->json($array);
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
