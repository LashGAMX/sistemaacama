<?php

namespace App\Http\Controllers\Informes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformesController extends Controller
{
    //
    public function index()
    {
        return view('informes.informes');
    }
}
