<?php

namespace App\Http\Controllers\Cotizacion;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ClienteGeneral;
use App\Models\SucursalCliente;
use Illuminate\Http\Request;

use App\Models\Cotizacion;
use App\Models\CotizacionMuestreo;
use App\Models\Norma;
use App\Models\SubNorma;
use App\Models\CotizacionParametros;
use App\Models\CotizacionPunto;
use App\Models\DireccionReporte;
use App\Models\NormaParametros;
use App\Models\PrecioCatalogo;
use App\Models\PrecioPaquete;
use App\Models\SeguimientoAnalsis;
use App\Models\TipoMuestraCot;
use App\Models\PromedioCot;
use App\Models\Sucursal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use PDF;
use Mpdf\Mpdf;


class CotizacionController extends Controller
{
    //
    public function index()
    {
        //Vista Cotización
        $model = DB::table('ViewCotizacion')->orderBy('Id_cotizacion', 'DESC')->get();
        return view('cotizacion.cotizacion', compact('model'));
    }
    public function getClientesIntermediarios(Request $res){
        $model = ClienteGeneral::where('Id_intermediario',$res->id)->get();

        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDataCliente(Request $res){
        $model = SucursalCliente::where('Id_sucursal', $res->id)->first();
        $direccion = DireccionReporte::where('Id_sucursal',$model->Id_sucursal)->get();

        $data = array(
            'model' => $model,
            'direccion' => $direccion,
        ); 

        return response()->json($data);
    }
    public function getSucursal(Request $res){ 
        $model = SucursalCliente::where('Id_cliente', $res->id)->get();

        $data = array(
            'model' => $model,
        ); 

        return response()->json($data);
    }
    public function setCotizacion(){
        
    }
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at', null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at', null)->get();
        $frecuencia = DB::table('frecuencia001')->get();
        $subNormas = SubNorma::all();
        $servicios = DB::table('tipo_servicios')->get();
        $descargas = DB::table('tipo_descargas')->get();
        $metodoPago = DB::table('metodo_pago')->get(); 
        $estados = DB::table('estados')->get();
        $categorias001 = DB::table('ViewDetalleCuerpos')->get();
        $tipoMuestraCot = TipoMuestraCot::all();
        $promedioCot = PromedioCot::all();



        $data = array(
            'categorias001' => $categorias001,
            'tipoMuestraCot' => $tipoMuestraCot,
            'promedioCot' => $promedioCot,
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'subNormas' => $subNormas,
            'servicios' => $servicios,
            'descargas' => $descargas,
            'frecuencia' => $frecuencia,
            'estados' => $estados,
            'metodoPago' => $metodoPago,
            'version' => $this->version,
        );
        return view('cotizacion.create', $data);
    }
}
