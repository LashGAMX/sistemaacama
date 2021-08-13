<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\SolicitudesGeneradas;
use App\Models\CampoGenerales;
use App\Models\Cotizacion;
use App\Models\Clientes;
use App\Models\ProcesoAnalisis;
use App\Models\TipoDescarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class IngresarController extends Controller
{
    //
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
        return response()->json(
            compact('solicitud', 'model')
        );
    }

    public function generar(Request $request)
    {                
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $request->idSolicitud)->get();        
                
        return response()->json(
            compact('model')
        );
    }

    public function setIngresar(Request $request){
        $procModel = ProcesoAnalisis::where('Folio', $request->folio)->get();

        if($procModel->count()){
            $proceso = ProcesoAnalisis::find($request->folio);
            $proceso->Folio = $request->folio;
            $proceso->Descarga = $request->descarga;
            $proceso->Cliente = $request->cliente;
            $proceso->Empresa = $request->empresa;
            $proceso->Hora_entrada = $request->horaEntrada;
            $proceso->save();
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
