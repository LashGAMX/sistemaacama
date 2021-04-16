<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index($name)
    {
        

        echo $name;
        // $model = Sucursal::all();

        // return view('home.index',compact('name','model'));
    }
    public function create(Request $request)
    {
        echo "Nombre: ".$request->name;
        echo "Apellido: ".$_POST['last'];

    }
}
