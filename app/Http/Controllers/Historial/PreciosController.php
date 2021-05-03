<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreciosController extends Controller
{
    function index(){
        $idUser = Auth::user()->id;
        return view('historial/precios',compact('idUser'));
    }
}
