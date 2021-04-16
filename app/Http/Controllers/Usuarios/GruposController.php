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
            // Saber si ya esta registrado en otro grupo

            // Saber si esta regristrado en el mismo grupo
            $esDuplicado =  DB::table('grupos_usuarios')->where('Grupo', $request->grupo)
                ->where('Usuario', $request->usuario)->first();
            if ($esDuplicado) {
                return response()->json(300);
            }

            $esRegistrado = DB::table('grupos_usuarios')->where('Usuario', $request->usuario)->first();
            if ($esRegistrado) {
                #Registrarlo a un que ya este en otro grupo
                DB::insert('insert into grupos_usuarios (Grupo, Usuario) values (?, ?)', [$request->grupo, $request->usuario]);
                return response()->json(200);
            }

            // Registrar usuario
            DB::insert('insert into grupos_usuarios (Grupo, Usuario) values (?, ?)', [$request->grupo, $request->usuario]);
            return response()->json(100);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Metodo para la Tabla de Usuarios y GruposController
     */
    public function obtenerTablaGruposUsuarios(Request $request)
    {
        $html = '';
        $gruposUsuarios =  DB::table('viewgrupos')->where('Grupo', $request->id_grupo)->get();

        $html .= '<table class="table table-hover">';
        $html .=  '<thead>';
        $html .=   '<tr>';
        $html .=    '<th scope="col">#</th>';
        $html .=   '<th scope="col">Integrante</th>';
        $html .=  '<th scope="col">Acciónes</th>';
        $html .=  '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        foreach ($gruposUsuarios as $gruposUsuario) {
            $html .= '<tr>';
            $html .= '<th scope="row">' . $gruposUsuario->Id_grupos_usuarios . '</th>';
            $html .= '<td>' . $gruposUsuario->nombre . '</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-danger" onclick="eliminarUsuarioGrupo(' . $gruposUsuario->Usuario . ')">X</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return response()->json($html);
    }
    /**
     * Metodo para eliminar Usuario.
     */
    public function eliminarUsuarioGrupo(Request $request)
    {

        $isTrue =  DB::table('grupos_usuarios')->where('Grupo', $request->id_grupo)
            ->where('Usuario', $request->id_usuario)->delete();

        return response()->json($isTrue);
    }
}
