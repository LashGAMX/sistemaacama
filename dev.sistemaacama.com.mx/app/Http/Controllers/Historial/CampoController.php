<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        return view('historial/campo',compact('idUser'));       
    }

    public function capturar(){
        $idUser = Auth::user()->id;

        return view('historial/campo/campoCapturar', compact('idUser'));
    }

    public function datosGenerales(){
        $idUser = Auth::user()->id;

        return view('historial/campo/campoCapturaGenerales', compact('idUser'));
    }

    public function datosMuestreo(){
        $idUser = Auth::user()->id;

        return view('historial/campo/campoCapturaMuestreo', compact('idUser'));
    }

    public function datosCompuestos(){
        $idUser = Auth::user()->id;

        return view('historial/campo/campoCapturaCompuestos', compact('idUser'));
    }
}