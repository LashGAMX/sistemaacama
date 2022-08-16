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
        $model = Usuario::all();
        return view('usuarios.lista_usuarios', compact('model'));
    }
}
