<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnvasesController extends Controller
{
    public function index()
    {
        return view('analisisQ.envases');
    }
}
