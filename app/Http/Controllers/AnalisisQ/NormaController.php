<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Models\Norma;
use Illuminate\Http\Request;

class NormaController extends Controller
{
    public function index()
    {
        return view('analisisQ.norma'); 
    }
    public function show($id) 
    {
        $norma = Norma::find($id);
        return view('analisisQ.detalle_normas',compact('id','norma'));
    }
    public function details($id,$idSub) 
    {
        $norma = Norma::find($id);
        return view('analisisQ.detalle_normas',compact('id','norma','idSub'));
    }
}
  