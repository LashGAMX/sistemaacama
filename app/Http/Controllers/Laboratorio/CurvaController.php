<?php

namespace App\Http\Controllers\Laboratorio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurvaController extends Controller
{
    public function index(){
        return view('laboratorio/curva');
    }
}
