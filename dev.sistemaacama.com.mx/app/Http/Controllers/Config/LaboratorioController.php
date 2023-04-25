<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class LaboratorioController extends Controller
{
    //
    public function index()
    {
        $idUser = Auth::user()->id;
       return view('config/laboratorio',compact('idUser'));
    }
} 
   