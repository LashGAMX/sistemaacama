<?php

namespace App\Http\Controllers\Recursos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampoController extends Controller
{
    //
    public function index(){
        $idUser = Auth::user()->id;
        return view('recurso.campo',compact('idUser'));
    }
}