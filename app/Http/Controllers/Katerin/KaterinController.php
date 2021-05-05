<?php
namespace App\Http\Controllers\Katerin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
use Illuminate\Support\Facades\Auth;

class KaterinController extends Controller 
{
    public function index()
    {
        // $idUser = Auth::user()->id;
        return view('katerin.katerinFormula');
    } 
    public function index2()
    {
        // $idUser = Auth::user()->id;
        return view('katerin.katerinParametroFormula');
    } 
}
 