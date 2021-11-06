<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConcentracionController extends Controller
{
    //
    public function index()
    {
        return view('analisisQ.concentracion');
    }
}
