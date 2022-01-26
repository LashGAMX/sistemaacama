<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\Norma; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LimitesController extends Controller
{
    public function index()
    {
        return view('analisisQ.limites');
    }
    public function show($idNorma)
    {
        return view('analisisQ.limites',compact('idNorma'));
    }
    public function details($idNorma,$idParametro)
    {
        return view('analisisQ.limites',compact('idNorma','idParametro'));
    }   
}
 