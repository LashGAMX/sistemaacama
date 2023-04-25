<?php

use App\Http\Controllers\Librerias\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('pdf', [PdfController::class,'index']);
Route::get('exportarPdf', [PdfController::class,'exportarPdf']); 