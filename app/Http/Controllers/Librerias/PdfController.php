<?php

namespace App\Http\Controllers\Librerias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

Use PDF;

class PdfController extends Controller
{
    //
    public function index() 
    {
        return view('librerias.pdf.index');
    } 
    public function exportarPdf()
    {
        $pdf = PDF::loadView('librerias.pdf.exportar');
        return $pdf->download('invoice.pdf');
    }
}
 