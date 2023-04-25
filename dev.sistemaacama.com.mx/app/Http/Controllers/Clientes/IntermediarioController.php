<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntermediarioController extends Controller
{
    //
    public function index()
    {
        return view('clientes.intermediario');
    }
}
