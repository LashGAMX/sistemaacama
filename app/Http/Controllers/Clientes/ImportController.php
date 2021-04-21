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
        switch ($request->categoria) {
            case '1':
                # code...
                // Excel::import(new IntermediarioImport, $request->file('file'));
                break;   
            default:
                # code...
                break;
        }
    }
}
