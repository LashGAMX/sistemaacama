<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Models\GrupoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\GroupUser;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

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

    //net
//METODO INDEPENDIENTE PARA CONSULTAR UN GRUPO DENTRO DEL MODAL De edicion 
public function getGroupDetails($id)
{
    
        $group = Group::findOrFail($id);
        $members = GroupUser::where('group_id', $id)->with('user:id,name')->get()->map(function ($groupUser) {
            return [
                'id' => $groupUser->user->id,
                'name' => $groupUser->user->name,
            ];
        });
        $groupDetails = [
            'id' => $group->id,
            'name' => $group->name,
            'members' => $members,
        ];
        return response()->json($groupDetails);
        return response()->json(['error' => 'Error al cargar detalles del grupo'], 500);
  
}
//METODO PARA EDITAR UN GRUPO 
public function editGroup(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'users' => 'nullable|array',
        'users.*' => 'exists:users,id', // Verificar que los usuarios existan en la base de datos
    ]);

    try {
        // Obtener el grupo
        $group = Group::findOrFail($id);

        // Actualizar nombre del grupo
        $group->name = $request->input('name');
        $group->save();

        // Actualizar integrantes del grupo
        if ($request->has('users')) {
            $group->users()->sync($request->input('users'));
        } else {
            $group->users()->detach(); // Si no se envían usuarios, se eliminarán todos los integrantes
        }

        // Retornar el grupo actualizado con los usuarios
        $updatedGroup = Group::with('users')->findOrFail($id);

        return response()->json([
            'message' => 'Grupo actualizado correctamente',
            'group' => $updatedGroup,
        ]);
    } catch (\Exception $e) {
        // Manejo de errores si ocurre algún problema durante la actualización
        return response()->json(['error' => 'Error al actualizar el grupo'], 500);
    }
}

//METODO PARA CONSULTAR UN LOS PRUPOS POR USUARIOS     
public function getGroups()
{
    $userId = auth()->id();
    $roluser = auth()->user()->role_id;

    $groupIds = GroupUser::where('user_id', $userId)->pluck('group_id')->toArray();

    $groups = Group::whereIn('id', $groupIds)->with(['users', 'users:id,name'])->get()
                    ->map(function ($group) use ($userId, $roluser) 
                    {
                        $groupUser = GroupUser::where('group_id', $group->id)->where('user_id', $userId)->first();

                        return [
                            'id' => $group->id,
                            'name' => $group->name,
                            'creatorName' => User::find($group->Id_user_c)->name,
                            'isCreator' => $group->Id_user_c === $userId,
                            'members' => $group->users->map(function ($user) {
                                return [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                ];
                            }),
                            'canEdit' => $roluser == 1, 
                            'count_message' => $groupUser ? $groupUser->count_message : 0, 
                        ];
                    });
    return response()->json($groups);
}

//METODO PARA ASIGNAR LOS ASUARIO A UN GRUPO 
public function asignarUser()
{
    $users = User::all(['id', 'name']);
    return response()->json($users);
}

//METODO PARA GUARDAR UN GRUPO    
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'users' => 'required|array',
        'users.*' => 'exists:users,id',
        'color'=> 'required|string|max:255'
    ]);

    // Verifica si ya existe un grupo con el mismo nombre y creador (opcional, dependiendo de la lógica de tu aplicación)
    $existingGroup = Group::where('name', $request->name)
                        ->where('Id_user_c', auth()->id())
                        ->first();
    
    if ($existingGroup) {
        return response()->json(['error' => 'El grupo ya existe'], 409); // O algún otro código de error adecuado
    }

    // Crear el grupo
    $group = Group::create([
        'name' => $request->name, 
        'Id_user_c' => auth()->id()
    ]);

    // Asocia los usuarios seleccionados al grupo
    $group->users()->attach($request->users);

    return response()->json($group, 201);
}
//Metodo para guardar el color en Color.json 
public function saveColor(Request $request)
{
    $colorData = [
        'groupId' => $request->groupId,
        'color' => $request->color
    ];

    $path = public_path('js/color.json');
    $jsonData = json_decode(file_get_contents($path), true);
    $jsonData[] = $colorData;
    file_put_contents($path, json_encode($jsonData, JSON_PRETTY_PRINT));

    return response()->json($colorData, 201);
}


}


