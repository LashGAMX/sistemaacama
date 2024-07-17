<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\GroupUser;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
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
    $roluser = auth()->user()->Rol_user;

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
    
//METODO PARA CONSULTAR LOS MENSAJE DE UN GRUPO 
    public function messages(Group $group)
    {
        $messages = $group->messages()->with('user')->get();
        return response()->json($messages);
    }
    public function resetMessage(Group $group)
    {
        $userId = Auth::id();

        $groupUser = GroupUser::where('group_id', $group->id)
                              ->where('user_id', $userId)
                              ->first();

        // Actualiza el contador de mensajes a 0
        if ($groupUser) {
            $groupUser->update(['count_message' => 0]);
        }

        return response()->json(['message' => 'Contador de mensajes reseteado correctamente']);
    }
    public function updateColor(Request $request, $id)
    {
        $request->validate([
            'color' => 'required|string|max:7',
        ]);
    
        // Cargar colores desde el archivo JSON
        $colorsFilePath = public_path('js/color.json');
        $colors = json_decode(file_get_contents($colorsFilePath), true);
    
        // Buscar y actualizar el color correspondiente al groupId
        foreach ($colors as &$colorEntry) {
            if ($colorEntry['groupId'] == $id) {
                $colorEntry['color'] = $request->input('color'); // Actualiza el color existente
                break; // Salir del bucle una vez actualizado
            }
        }
    
        // Guardar el archivo JSON
        file_put_contents($colorsFilePath, json_encode($colors, JSON_PRETTY_PRINT));
    
        return response()->json(['message' => 'Color actualizado correctamente']);
    }
    
    
    


}
