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
        return view('cotizacion.cotizacion', compact('model', 'intermediarios', 'cliente', 'norma', 'subNormas'));
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
        $telefono = $request->telefono;
        $correo =  $request->correo;
        $estadoCotizacion = $request->estadoCotizacion;
        $tipoDescarga = $request->tipoDescarga;
        $clasifacionNorma =  $request->clasifacionNorma;
        $normaFormularioUno =  $request->normaFormularioUno;
        $frecuencia = $request->frecuencia;
        $tipoMuestra = $request->tipoMuestra;
        $promedio =  $request->promedio;
        $puntosMuestreo =  $request->puntosMuestreo;
        $reporte =  $request->reporte;
        $codiccionesVenta = $request->codiccionesVenta;

        $viaticos = $request->viaticos;
        $paqueteria = $request->paqueteria;
        $gastosExtras = $request->gastosExtras;
        $numeroServicio = $request->numeroServicio;
        $kmExtra = $request->kmExtra;
        $precioKm = $request->precioKm;
        $precioKmExtra = $request->precioKmExtra;
        $observacionInterna = $request->observacionInterna;
        $observacionCotizacion = $request->observacionCotizacion;
        $tarjeta = $request->tarjeta;
        $tiempoEntrega = $request->tiempoEntrega;
        $cotizacion = Cotizaciones::withTrashed()->get();
        $num = count($cotizacion);
        $num++;
        $valoresParametros = $request->valoresParametros;
        try {
            $newCotizacion =  Cotizaciones::create([
                'Cliente' => $clienteManual,
                'Folio_servicio' => '21-92/' . $num,
                'Cotizacion_folio' => '21-92/' . $num,
                'Empresa' => $atencionA,
                'Servicio' => $tipoServicio,
                'Fecha_cotizacion' => $fechaCotizacion,
                'Supervicion' => 'por Asignar',
                'deleted_at' => NULL,
                'created_by' =>  'David Barrita',
                'Telefono' => $telefono,
                'Correo' => $correo,
                'Tipo_descarga' => $tipoDescarga,
                'Tipo_servicio' => $tipoServicio,
                'Estado_cotizacion' => $estadoCotizacion,
                'Puntos_muestreo' => $puntosMuestreo,
                'Promedio' =>  $promedio,
                'Tipo_muestra' => $tipoMuestra,
                'frecuencia' => $frecuencia,
                'Norma_formulario_uno' => $normaFormularioUno,
                'clasificacion_norma' => $clasifacionNorma,
                'Reporte' => $reporte,
                'condicciones_venta' =>  $codiccionesVenta,
                'Viaticos' =>  $viaticos,
                'Paqueteria' => $paqueteria,
                'Gastos_extras' => $gastosExtras,
                'Numero_servicio' => $numeroServicio,
                'Km_extra' => $kmExtra,
                'observacionInterna' => $observacionInterna,
                'observacionCotizacion' => $observacionCotizacion,
                'tarjeta' => $tarjeta,
                'tiempoEntrega' => NULL,
                'precioKmExtra' => $precioKmExtra
            ]);
            #Alamacenar parametros de la cotización
            if (@$valoresParametros) {
                $value = sizeof($valoresParametros);
                #Recorrer
                for ($i = 0; $i <= 10; $i++) {
                    DB::table('evaluacion_parametros')->insert([
                        'Id_cotizacion' => $value,
                        'Id_parametro' => $value,
                        'Es_extra' => 0
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return $th;
        }

        return back();
    }

    /**
     * Metodo para Obtner parametros
     */
    public function obtenerParametros(Request $request)
    {
        $html = "";
        $subNorma =  $request->id_subnorma;
        $parametros =  DB::table('ViewNormaParametro')->where('Id_norma', 2)->get();
        foreach ($parametros as $parametro) {
            $html .= "<option value='" . $parametro->Id_norma_param . "'>" . $parametro->Parametro . "</option>";
        }
        echo $html;
    }
    /**
     * Obtener La Subnorma
     */
    public function obtenerClasificacion(Request $request)
    {
        $html = "";
        $norma = $request->id_norma;
        $parametrosDos =  DB::table('sub_normas')->where('Id_norma',  $norma)->get();
        foreach ($parametrosDos as $parametroDos) {
            $html .= "<option value='" . $parametroDos->Id_subnorma . "'>" . $parametroDos->Clave . "</option>";
        }
        echo $html;
    }
}
