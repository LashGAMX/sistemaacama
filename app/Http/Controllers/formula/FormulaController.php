<?php

namespace App\Http\Controllers\formula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class FormulaController extends Controller
{
    public function index()
    {
        // $idUser = Auth::user()->id;
        return view('formulas.formula');
    } 
}
