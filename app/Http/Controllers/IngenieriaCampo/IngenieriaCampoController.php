<?php

namespace App\Http\Controllers\IngenieriaCampo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngenieriaCampoController extends Controller
{
    //----------------------------------------------------/
    /****************************************************/
    //MODULO DE CONTROL DE INSTRUMENTOS//
    
    //
    public function index(){
        $idUser = Auth::user()->id;
        return view('IngenieriaCampo.ingenieriaCampo',compact('idUser'));
    }
    
    public function controlInstrumentos()
    {
        $idUser = Auth::user()->id;
        return view('IngenieriaCampo/instrumentos', compact('idUser'));
    }

}
