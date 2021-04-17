<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UsuariosController extends Controller
{
    //
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.usuarios', compact('usuarios'));
    }

    public function cambiarPassword(Request $request)
    {
        $usuario = Usuario::find($request->id_cliente);
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return response()->json($usuario);
    }
}
