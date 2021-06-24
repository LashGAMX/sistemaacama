<?php

namespace App\Http\Controllers\Christian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChristianController extends Controller
{
    //
    public function index(){
        $idUser = Auth::user()->id;
        return view('christian.christian',compact('idUser'));
    }
}
