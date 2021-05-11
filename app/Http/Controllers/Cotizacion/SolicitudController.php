<?php

namespace App\Http\Controllers\Cotizacion;

use App\Http\Controllers\Controller;
use App\Models\ContactoCliente;
use App\Models\DireccionReporte;
use App\Models\Intermediario;
use App\Models\SucursalCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    // 
    public function index()
    {
        return view('cotizacion.solicitud');
    } 
    public function create()
    {
        $intermediario = DB::table('ViewIntermediarios')->get();
        $cliente = DB::table('ViewGenerales')->get();
        

        return view('cotizacion.createSolicitud',
        compact(
            'intermediario',
            'cliente'
        ));
    }
    public function getSucursal(Request $request)
    {
        $contacto = ContactoCliente::wherE('Id_cliente',$request->cliente)->get();
        $sucursal = SucursalCliente::where('Id_cliente',$request->cliente)->get();
        $data = array(
            'sucursal' => $sucursal,
            'contacto' => $contacto,
        );
        return response()->json($data);
    }
    public function getDatoIntermediario(Request $request)
    {
        $intermediario = DB::table('ViewIntermediarios')->where('Id_cliente',$request->idCliente)->first();
        $data = array(
            'intermediario' => $intermediario,
        );
        return response()->json($data);
    }
    public function getDireccionReporte(Request $request)
    {
        // $direccion = DB::table('di')
        $direccion = DireccionReporte::where('Id_sucursal',$request->idSucursal)->get();
        $data = array(
            'direccion' => $direccion,
        );
        return response()->json($data);
    }
    public function setContacto(Request $request)
    {
        
    }
}
  
 