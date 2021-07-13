<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UsuarioApp;
use Illuminate\Http\Request;

class CampoAppController extends Controller
{
    public function login(Request $request)
    {
        $model = UsuarioApp::where('User',$request->user)
            ->where('UserPass',$request->pass) 
            ->get();
        if($model->count()){
            $success = true;    
        }else{
            $success = false;
        }
        $data = array(
            'usuarios' => UsuarioApp::all(), 
            'response' => $success,
            'data' => $model,  
        );
        return response()->json($data); 
    } 
}
