<?php

namespace App\Http\Controllers\Ingresar;

use App\Http\Controllers\Controller;
use App\Models\SolicitudesGeneradas;
use App\Models\CampoGenerales;
use App\Models\Cotizacion;
use App\Models\Clientes;
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

        $model = DB::table('ViewCotizacion')->get();
        return view('ingresar.ingresar',compact('idUser', 'model'));        
    }

    public function generar(Request $request)
    {                
        $model = DB::table('ViewCotizacion')->where('Id_cotizacion', $request->idSolicitud)->get();        
                
        return response()->json(
            compact('model')
        );
    }
}
