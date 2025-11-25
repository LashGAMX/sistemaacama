<?php

namespace App\Http\Controllers\Beto;

use App\Http\Controllers\Controller; 
use App\Models\Signo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use FormulaParser\FormulaParser;

class BetoController extends Controller
{

    public function animacion()
    {
        return view('beto.animacion');
    }

    public function capturacampo2()
    {
        return view('campo.captura2');
    }

   public function listaMuestreo()
{
    $query = SolicitudesGeneradas::orderBy('Id_solicitud', 'DESC');

    // Filtrar según rol
    if (!in_array(Auth::user()->role_id, [1, 15, 7])) {
        $query->where('Id_muestreador', Auth::id())
              ->limit(1000);
    }

    // Traer solicitudes junto con su usuario
    $solicitudes = $query->with('muestreador') // relación en el modelo
                         ->get();

    // Obtener datos relacionados desde la vista (join directo en lugar de foreach)
    $solicitudes = $solicitudes->map(function ($item) {
        $temp = DB::table('ViewSolicitud2')
            ->where('Id_solicitud', $item->Id_solicitud)
            ->first();

        return [
            'id'       => $item->Id_solicitud,
            'cliente'  => optional($temp)->Empresa_suc,
            'fecha'    => optional($temp)->Fecha_muestreo,
            'norma'    => optional($temp)->Clave_norma,
            'usuario'  => optional($item->muestreador)->name,
        ];
    });

    return view('campo.captura2', ['solicitudes' => $solicitudes]);
}



}