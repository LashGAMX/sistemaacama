<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Laboratorio\FqController;
use App\Models\PlantillasFq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlantillasController extends Controller
{
    //
    public function index()
    {
        $idUser = Auth::user()->id;
       return view('config/plantillas/index',compact('idUser'));
    }
    public function bitacoras($tipo)
    {
        $model = PlantillasFq::all();
        $data = array(
            'model' => $model, 
            'tipo' => $tipo,
        );
        return view('config/plantillas/bitacoras',$data);
    }
    public function getPlantillas(Request $res)
    {
        switch ($res->tipo) {
            case 1://Fq
                $model  = DB::table('ViewPlantillasFq')->get();
                break;
            default:
                
                break;
        }
        
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDetalleBitacora(Request $res){
        switch ($res->tipo) {
            case 1: // Fq
                $model = PlantillasFq::where('Id_plantilla',$res->id)->get();
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'tipo' => $res->tipo,
            'model' => $model,
        );
        return response()->json($data);
    }
    public function setPlantilla(Request $res)
    {
        switch ($res->tipo) {
            case 1: // Fq
                $model = PlantillasFq::where('Id_plantilla',$res->id)->get();
                $model[0]->Texto = $res->texto;
                $model[0]->save();
                break;
            default:
                # code...
                break;
        }
        $data = array(
            'tipo' => $res->tipo,
            'model' => $model,
        );
        return response()->json($data);  
    }
} 
    