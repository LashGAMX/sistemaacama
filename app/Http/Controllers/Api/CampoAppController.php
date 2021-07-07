<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\UsuarioApp;
use Illuminate\Http\Request;

class CampoAppController extends Controller
{
    //
    public function login($user,$pass)
    { 
        $model = UsuarioApp::where('User',$user)->where('UserPass',$pass)->first(); 
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
    public function getUser()
    {
        $model = UsuarioApp::all();
        $data = array(
            'response' => true,
            'data' => $model,
        );
        return response()->json($data);
    }
    public function user(Request $request)
    {
        $model = UsuarioApp::where('User',$request->user)->where('UserPass',$request->pass)->first(); 
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
    public function getListaMuestre(Request $request)
    {
        $model = DB::table('ViewSolicitudGenerada')->where('Id_muestreador',)->get();
        return view('campo.listaMuestreo',compact('model'));
    }
}
