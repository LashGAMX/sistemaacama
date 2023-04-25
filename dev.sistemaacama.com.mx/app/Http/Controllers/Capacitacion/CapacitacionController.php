<?php

namespace App\Http\Controllers\Capacitacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CapacitacionController extends Controller
{
    //
    public function index()
    {
        return view('capacitacion.capacitacion');
    }
}
