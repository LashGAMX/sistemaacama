<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Encuesta\Encuesta;
use Composer\Util\Http\Response;
use Illuminate\Http\Request;

class EncuestaController extends Controller
{
    //
    public function getData()
    {
        $encuestas = Encuesta::all();
        $data = array(
            'data' => $encuestas,
        );
        return response()->json($data);
    }
}
