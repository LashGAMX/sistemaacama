<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\GroupUser;


class GrupoController extends Controller
{

//Metodo para consultar a los Usuarios     
public function asignarUser(Request $request)
{
    $buscar = $request->input('q', '');
    $users = Users::where('name', 'like', '%' . $buscar . '%')->get(['id', 'name']);

    return response()->json($users);
}

//Metodo para Guardar el grupo si tu role_id es el autentificado         
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'usuarios' => 'required|array',
            'usuarios.*' => 'exists:users,id',
            'color' => 'required|string|max:255'
        ]);
    
        // Verificar si el grupo ya existe
        $existingGroup = Group::where('name', $request->name)
                              ->where('Id_user_c', auth()->id())
                              ->first();
    
        if ($existingGroup) {
            return response()->json(['error' => 'El grupo ya existe'], 409);
        }
    
        // Crear el grupo
        $group = Group::create([
            'name' => $request->name,
            'Id_user_c' => auth()->id()
        ]);
    
        // Asociar los usuarios al grupo
        $group->users()->attach($request->usuarios);
    
        // Datos del color a añadir
        $colorData = [
            'groupId' => $group->id,
            'color' => $request->color
        ];
    
        $path = public_path('js/chat/color.json');
    
        $jsonData = json_decode(file_get_contents($path), true);
        
        // Asegurarse de que los datos leídos sean un array
        if (!is_array($jsonData)) {
            $jsonData = [];
        }
    
        $jsonData[] = $colorData;
    
        // Guardar el archivo JSON
        file_put_contents($path, json_encode($jsonData, JSON_PRETTY_PRINT));
    
        return response()->json(['msg' => 'Grupo creado exitosamente'], 201);
    }

// MÉTODO PARA CONSULTAR LOS GRUPOS POR USUARIOS     
    public function getGroups()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $userId = auth()->id();
        $roluser = auth()->user()->role_id; // Asegúrate de que esta propiedad esté bien
    
        // Obtener los IDs de los grupos a los que pertenece el usuario
        $groupIds = GroupUser::where('user_id', $userId)->pluck('group_id');
    
        // Obtener los grupos con sus usuarios y contar mensajes
        $groups = Group::with('users:id,name') // Cargar usuarios del grupo
            ->whereIn('id', $groupIds)
            ->get()
            ->map(function ($group) use ($userId, $roluser) {
                $groupUser = GroupUser::where('group_id', $group->id)
                    ->where('user_id', $userId)
                    ->first();
    
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'creatorName' => optional(Users::find($group->Id_user_c))->name,
                    'isCreator' => $group->Id_user_c === $userId,
                    'members' => $group->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                        ];
                    }),
                    'canEdit' => $roluser == 1,
                    'count_message' => $groupUser->count_message ?? 0,
                ];
            });
    
        return response()->json($groups);
    }
    



//METODO INDEPENDIENTE PARA CONSULTAR UN GRUPO DENTRO DEL MODAL De edicion 
public function getGroupDetails($id)
{
    try {
        $group = Group::findOrFail($id);
        $members = GroupUser::where('group_id', $id)
            ->with('user:id,name')
            ->get()
            ->map(function ($groupUser) {
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
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al cargar detalles del grupo'], 500);
    }
}


//METODO PARA EDITAR UN GRUPO 
public function editGroup(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'users' => 'nullable|array',
        'users.*' => 'exists:users,id', // Verificar que los usuarios existan en la base de datos
    ]);

    try {
        // Obtener el grupo
        $group = Group::findOrFail($request->route('id'));

        // Actualizar nombre del grupo
        $group->name = $request->input('name');
        $group->save();

        if ($request->has('users')) {
            $group->users()->sync($request->input('users'));
        } else {
            $group->users()->detach(); 
        }

        // Retornar el grupo actualizado con los usuarios
        $updatedGroup = Group::with('users')->findOrFail($group->id);

        return response()->json([
            'message' => 'Grupo actualizado correctamente',
            'group' => $updatedGroup,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar el grupo'], 500);
    }
}


//METODO PARA ACTUALIZAR EL COLOR DEL JSON 
public function updateColor(Request $request)
{
    $request->validate([
        'color' => 'string|max:7',
    ]);

    // Cargar colores desde el archivo JSON
    // $colorsFilePath = public_path('public/js/chat/color.json');
    $colorsFilePath = public_path('js/chat/color.json');

    $colors = json_decode(file_get_contents($colorsFilePath), true);

    // Buscar y actualizar el color correspondiente al groupId
    $groupId = $request->route('id');
    $colorUpdated = false;

    foreach ($colors as &$colorEntry) {
        if ($colorEntry['groupId'] == $groupId) {
            $colorEntry['color'] = $request->input('color'); // Actualiza el color existente
            $colorUpdated = true;
            break; // Salir del bucle una vez actualizado
        }
    }

    if ($colorUpdated) {
        // Guardar el archivo JSON
        file_put_contents($colorsFilePath, json_encode($colors, JSON_PRETTY_PRINT));
        return response()->json(['message' => 'Color actualizado correctamente']);
    } else {
        return response()->json(['error' => 'Grupo no encontrado'], 404);
    }
}
public function CountGrupo($id)
{
    // Asegúrate de que el ID se recibe correctamente
    // dd($id); // Descomentar para depuración

    $userId = Auth::id();

    // Verifica si $id es un número entero válido
    if (!is_numeric($id)) {
        return response()->json(['error' => 'ID inválido'], 400);
    }

    // Encuentra el registro de GroupUser usando el ID del grupo
    $groupUser = GroupUser::where('group_id', $id)
                          ->where('user_id', $userId)
                          ->first();

    // Determina el mensaje de respuesta
    if ($groupUser && $groupUser->count_message > 0) {
        $groupUser->update(['count_message' => 0]);
        $mensaje = "Contador actualizado";
    } else {
        $mensaje = "No hay mensajes";
    }

    // Devuelve la respuesta JSON
    return response()->json(['message' => $mensaje]);
}

public function messages(Group $group)
{
    $messages = $group->messages()->with('user')->get();
    return response()->json($messages);
}




}
