<?php

namespace App\Http\Controllers\AnalisisQ;

use App\Http\Controllers\Controller;
use App\Imports\AnalisisQ\ParametrosImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ImportarController extends Controller
{ 
    //
    public function index()
    {
        return view('analisisQ.importar');
    }
    public function create(Request $request)
    {
        switch ($request->submodulo) {
            case 1:
                # code...
                Excel::import(new ParametrosImport,$request->file('file')); 
                break;
            case 2:
                break;
            case 3:
                break;
            default:
                # code...
                break;
        }
    }
}
