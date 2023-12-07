<?php

namespace App\Http\Controllers\kpi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    //
    public function index(){
        return view('kpi.kpi');
    }
}
