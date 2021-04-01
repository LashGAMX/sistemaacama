<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cotizaciones;
use App\Models\IntermediariosView;
use App\Models\Clientes;
use App\Models\Norma;
use App\Models\SubNorma;
use App\Models\DetallesTipoCuerpo;
use App\Models\NormaParametroView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class CotizacionController extends Controller
{

    /**
     * Retorna la Pagina Principal del Modulo de Cotización
     */
    public function index()
    {
        //Vista Cotización
        $model = Cotizaciones::All();
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        $clasificacion = DetallesTipoCuerpo::All();
        $subNormas = SubNorma::All();
        return view('cotizacion.cotizacion',compact('model','intermediarios','cliente','norma','subNormas'));
    }
    /**
     * Metodo para Registrar Cotización
     */
    public function registrar(Request $request)
    {
        $now = Carbon::now();
        $now->year;
        $now->month;
        $clienteManual =   $request->atencionA;
        $tipoServicio = $request->tipoServicio;
        $atencionA = $request->clienteManual;
        $fechaCotizacion = $request->fechaCotizacion;

        $cotizacion = Cotizaciones::withTrashed()->get();
        $num = count($cotizacion);
        $num++;
           Cotizaciones::create([
            'Cliente' => $clienteManual,
            'Folio_servicio' => '21-89/'.$num,
            'Cotizacion_folio' => '21-89/'.$num,
            'Empresa' => $atencionA,
            'Servicio' => $tipoServicio,
            'Fecha_cotizacion' => $fechaCotizacion,
            'Supervicion' => 'por Asignar',
            'deleted_at' => NULL,
            'created_by' =>  'David Barrita'
        ]);

        return back();
    }

    /**
     * Metodo para Obtner parametros
     */
    public function obtenerParametros(Request $request)
    {
        $html="";
         $subNorma =  $request->id_subnorma;
        $parametros =  DB::table('ViewNormaParametro')->where('Id_norma',2)->get();
        foreach($parametros as $parametro){
            $html.="<option value='".$parametro->Id_norma_param."'>".$parametro->Clave."</option>";
        }
        echo $html;

    }
}
