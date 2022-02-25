<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use App\Models\TipoReporte;
use Illuminate\Http\Request;

class InformesController extends Controller
{
    //
    public function index()
    {
        $tipoReporte = TipoReporte::all();
        return view('informes.informes',compact('tipoReporte'));
    }
}
