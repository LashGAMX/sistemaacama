<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    public function index(){
        $idUser = Auth::user()->id;
        return view('campo.campo', compact('idUser'));
    }
    
    //
    public function termometros()
    {
        return view('config.campo');  
    }
}
 