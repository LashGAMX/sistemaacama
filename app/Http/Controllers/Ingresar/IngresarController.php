<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\ProcedimientoAnalisis;
use App\Models\ProcesoAnalisis;
use App\Models\SeguimientoAnalisis;
use App\Models\Solicitud; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngresarController extends Controller
{    

    public function index(){
        $idUser = Auth::user()->id;

        $model = DB::table('ViewSolicitud')->get();
        return view('ingresar.ingresar',compact('idUser', 'model'));        
    }    

    public function recepcion(){
        $idUser = Auth::user()->id;

        $model = DB::table('ViewSolicitud')->get();
        return view('ingresar.recepcion',compact('idUser', 'model'));    
    }

    public function buscarFolio(Request $request){
        $model = Db::table('ViewSolicitud')->where('Folio_servicio',$request->folioSol)->first();
        $array = array(
            'model' => $model,
        );
        return response()->json($array);
    }

    //pp 
    public function fechaFinSiralab(Request $request){        
        $siralab = DB::table('ViewPuntoMuestreoSir')->where('Id_sucursal', $request->sucursal)->first();
        return response()->json(compact('siralab'));
    }
 
    public function setIngresar(Request $request){
        $model = ProcesoAnalisis::where('Id_solicitud',$request->idSol)->get();
        $seguimiento = SeguimientoAnalisis::where('Id_servicio',$request->idSol)->first();
        if($model->count()){

        }else{
            $seguimiento->Recepcion = 1;
            $seguimiento->save();
            
            ProcesoAnalisis::create([ 
                'Id_solicitud' => $request->idSol,
                'Folio' => $request->folio,
                'Descarga' => $request->descarga,
                'Cliente' => $request->cliente,
                'Empresa' => $request->empresa,
                'Hora_entrada' => $request->horaEntrada,
            ]);
        }

        $array = array(
            'model' => $model,
        );
        return response()->json($array);
        
    }

    //Método para obtener la fecha de conformación
    public function fechaConformacion(Request $request){
        $fechaC = DB::table('ph_muestra')->where('Id_solicitud', $request->idSolicitud)->get();

        return response()->json(compact('fechaC'));
    }

    //Método para obtener la procedencia con previa cotización
    public function procedencia(Request $request){
        $cotizacion_muestreos = DB::table('cotizacion_muestreos')->where('Id_cotizacion', $request->idCotizacion)->first();

        if($cotizacion_muestreos !== null){
            $estado = DB::table('estados')->where('Id_estado', $cotizacion_muestreos->Estado)->first();
        }

        return response()->json(compact('estado'));
    }


    //----------------------------------MÓDULO GENERAR-------------------------------------
    public function genera2(){
        $idUser = Auth::user()->id;
        $model = DB::table('ViewSolicitud')->get();

        return view('ingresar.solicitud', compact('idUser', 'model'));
    }

    public function buscadorGen(Request $request){
        $model = ProcesoAnalisis::where('Folio', $request->busquedaIn)->first();        

        return response()->json(compact('model'));
    }
}