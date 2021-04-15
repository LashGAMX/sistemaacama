<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Imports\Clientes\IntermediarioImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

// use Public\assets\PHPExcel\Classes\PHPExcel.php;

class ImportController extends Controller
{
    //
    public function index()
    {

        return view('clientes.importar');
    }
    public function create(Request $request)
    {
        $file = $request->file('file');
        var_dump($file);
        // Excel::import(new IntermediarioImport, $file);
    }
}
