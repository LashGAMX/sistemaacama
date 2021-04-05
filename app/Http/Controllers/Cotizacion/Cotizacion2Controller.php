<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Cotizacion\Cotizacion;
use App\Models\Clientes;
use App\Models\Cotizaciones;
use App\Models\DetallesTipoCuerpo;
use App\Models\IntermediariosView;
use App\Models\Norma;
use App\Models\SubNorma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cotizacion2Controller extends Controller
{
    //
    public function index()
    {
        //Vista Cotización
        $model = Cotizacion::All();
        $intermediarios = IntermediariosView::All();
        $cliente = Clientes::All();
        $norma = Norma::All();
        $clasificacion = DetallesTipoCuerpo::All();
        $subNormas = SubNorma::All();
        return view('cotizacion.cotizacion', compact('model', 'intermediarios', 'cliente', 'norma', 'subNormas'));
    }
    public function create()
    {
        $intermediarios = DB::table('ViewIntermediarios')->where('deleted_at',null)->get();
        $generales = DB::table('ViewGenerales')->where('deleted_at',null)->get();
        $normas = Norma::all();
        $subNormas = SubNorma::all();

        $data = array(
            'intermediarios' => $intermediarios,
            'generales' => $generales,
            'normas' => $normas,
            'subNormas' => $subNormas,
        );
        return view('cotizacion.create',$data);
    }
    public function getCliente()  
    { 
        $id = $_POST['cliente'];
        $model = DB::table('ViewGenerales')->where('Id_cliente',$id)->first();
        return response()->json($model);
    }
    public function getSubNorma()
    {
        $id = $_POST['norma'];
        $model = DB::table('ViewPrecioPaq')->where('Id_paquete',$id)->get();
        $data = array(
            'model' => $model,
        );
        return response()->json($data);
    }
    public function getDatos2() 
    { 
        $id = $_POST['intermediario'];
        $idSub = $_POST['idSub'];
        $intermediarios = DB::table('ViewIntermediarios')->where('Id_cliente',$id)->first();
        $subnorma = DB::table('sub_normas')->where('Id_subnorma',$idSub)->first();
        
        $precio = DB::table('ViewPrecioPaqInter')->where('Id_norma',$idSub)->first();

        if($precio != NULL){

        }else{
            $precio = DB::table('ViewPrecioPaq')->where('Id_paquete',$idSub)->first();
        }
 
        $data = array(
            'intermediarios' => $intermediarios,
            'subnorma' => $subnorma,
            'precio' => $precio,
        );
        return response()->json($data);
    }
    public function setCotizacion(Request $request){
        
        $now = Carbon::now();
        $year = $now->format('y');
 
        // $cotizacion = Cotizaciones::withTrashed()->get();
        // $num = count($cotizacion);
        // $num++;
        // // @$valoresParametros = @$request->valoresParametros;

        $newCotizacion =  Cotizaciones::create([
            'Cliente' => $request->nombreCliente,
            // 'Folio_servicio' => '21-92/' . $year,
            'Cotizacion_folio' => '21-92/' . $year,
            'Empresa' => $request->atencion,
            'Servicio' => $request->tipoServicio,
            'Fecha_cotizacion' => $request->fecha,
            'Supervicion' => 'por Asignar',
            'Telefono' => $request->telefono,
            'Correo' => $request->correo,
            'Tipo_descarga' => $request->tipoDescarga,
            'Tipo_servicio' => $request->tipoServicio,
            'Puntos_muestreo' => $request->puntosMuestreo,
            'Promedio' =>  $request->promedio,
            'Tipo_muestra' => $request->tipoMuestra,
            'frecuencia' => $request->frecuencia,
            // 'Norma_formulario_uno' => $normaFormularioUno,
            'clasificacion_norma' => $request->norma,
            'Reporte' => $request->tipoReporte,
            'condicciones_venta' =>  $request->codiccionesVenta,
            'Viaticos' =>  $request->viaticos,
            'Paqueteria' => $request->paqueteria,
            'Gastos_extras' => $request->gastosExtras,
            'Numero_servicio' => $request->numeroServicio,
            'Km_extra' => $request->kmExtra,
            'observacionInterna' => $request->observacionInterna,
            'observacionCotizacion' => $request->observacionCotizacion,
            'tarjeta' => $request->tarjeta,
            'tiempoEntrega' => NULL,
            'precioKmExtra' => $request->precioKmExtra
        ]);

        return redirect()->to('admin/cotizacion');
    }
    public function fecha()
    { 
        $fecha = Carbon::now();
        var_dump($fecha->format('yday'));
        $hoy = getdate();
print_r($hoy['weekday']);
    }

}