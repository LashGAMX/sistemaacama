<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\UsuarioApp;
use Illuminate\Http\Request;

class CampoAppController extends Controller
{
    //
    public function login(Request $request)
    { 
        $model = UsuarioApp::where('User',$request->user)->where('UserPass',$request->userPass)->first();
        $response = false;
        if($model->count())
        {
           $response = true;
        }
        $data = array(
            'response' => $response,
            'data' => $model, 
        );
 
        return response()->json($data);
        
    }
}
