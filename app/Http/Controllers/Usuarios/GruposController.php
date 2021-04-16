<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Models\GrupoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class GruposController extends Controller
{

    public function index()
    {
        $grupos = DB::table('grupos')->paginate(1);
        $usuarios = Usuario::all();
        return view('usuarios.grupos', compact('grupos', 'usuarios'));
    }

    /**
     * Metodo para crear un grupo
     */

    public function guardar(Request $request)
    {
        try {
            $gruposNew = new Grupo;
            $gruposNew->Titulo =  $request->titulo;
            $gruposNew->Comentario = $request->comentario;
            $gruposNew->save();
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json(true);
    }
    /**
     * Metodo para Editar Grupo
     */
    public function editar(Request $request)
    {
        try {
            $grupoEdit = DB::table('grupos')->where('Id_grupos', $request->id_edit)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
        return response()->json($grupoEdit);
    }
    /**
     * Metodo para Actualizar Grupo
     */
    public function actualizar(Request $request)
    {
        try {
            $grupo  = DB::table('grupos')
                ->where('Id_grupos', $request->id_edit)
                ->update([
                    'Titulo' => $request->titulo,
                    'Comentario' => $request->comentario
                ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json($grupo);
    }
    /**
     * Agregar a Usuario
     */
    public function agregarUsuario(Request $request)
    {
        try {
            $grupoUsuario = new GrupoUsuario;
            $grupoUsuario->Grupo = $request->grupo;
            $grupoUsuario->Usuario = $request->usuario;

            return response()->json(100);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
