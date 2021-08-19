<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\ProcesoAnalisis;
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

    public function genera2(){
        $idUser = Auth::user()->id;
        $model = DB::table('ViewSolicitud')->get();

        return view('ingresar.solicitud', compact('idUser', 'model'));
    }

    public function buscador(Request $request){        
        $solicitud = DB::table('ViewSolicitud')->where('Folio_servicio', "like", $request->texto."%")->first();
        $model = ProcesoAnalisis::where('Folio', "like", $request->texto."%")->first();
        
        return response()->json(compact('solicitud', 'model'));
    }


    public function setIngresar(Request $request){
        $procModel = ProcesoAnalisis::where('Folio', $request->folio)->get();

        if($procModel->count()){
            $proceso = ProcesoAnalisis::find($request->folio);
            //$proceso->Folio = $request->folio;
            //$proceso->Descarga = $request->descarga;
            //$proceso->Cliente = $request->cliente;
            //$proceso->Empresa = $request->empresa;
            //$proceso->Hora_entrada = $request->horaEntrada;
            //$proceso->save();
        }else{
            ProcesoAnalisis::create([
                'Folio' => $request->folio,
                'Descarga' => $request->descarga,
                'Cliente' => $request->cliente,
                'Empresa' => $request->empresa,
                'Hora_entrada' => $request->horaEntrada
            ]);
        }
    }
}