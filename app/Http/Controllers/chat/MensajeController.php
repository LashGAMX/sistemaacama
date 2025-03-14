<?php

namespace App\Http\Controllers\chat;
use App\Models\Message;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;



class MensajeController extends Controller
{
// Metodo para realizar el contador general por usuarios 
public function ContGen ()
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'No autorizado'], 401);
    }

    $messageCount = GroupUser::where('user_id', $userId)->sum('count_message') ?? 0;

    return response()->json($messageCount);
}


//METODO PARA CREAR UN NUEVO MENSAJE POR Grupo   
      public function mensaje(Request $request)
      {
          try {
              $request->validate([
                  'group_id' => 'required|exists:groups,id',
                  'message' => 'nullable|string',
                  'file' => 'nullable|file|max:51200',
              ]);
      
              $data = $request->only(['group_id', 'message']);
              $data['user_id'] = auth()->id();
      
              if ($request->hasFile('file')) {
                  $file = $request->file('file');
                  $nombreArchivo = $file->getClientOriginalName();
                  $path = $file->storeAs('files', $nombreArchivo, 'local'); 
                  
                  $data['file'] = $nombreArchivo; 
              }
      
              if (empty($data['message']) && empty($data['file'])) {
                  return response()->json(['error' => 'No has escrito nada ni adjuntado un archivo.'], 400);
              }
              
      
              $message = Message::create($data);
      
              return response()->json($message, 201);
      
          } catch (\Exception $e) {
              return response()->json(['error' => 'Error al procesar la solicitud. Detalles: ' . $e->getMessage()], 500);
          }
      }




 
}
