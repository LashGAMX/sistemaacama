<?php

namespace App\Http\Controllers;

use App\Events\chat;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\File;
use App\Events\SendMessage;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Event;

class MessageController extends Controller
{
    //METODO PARA CREAR UN NUEVO MENSAJE POR Grupo   
    public function store(Request $request)
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
                $path = $file->storeAs('files', $nombreArchivo, 'local'); // Guarda el archivo en storage/app/files
                
                $data['file'] = $nombreArchivo; // Guarda solo el nombre del archivo en la base de datos, sin 'files/'
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
    //METODO PARA DESCARGAR UN FILE 
    public function download($file)
    {
        try {
            $filePath = storage_path('app/files/' . $file);
        
            if (File::exists($filePath)) {
                return response()->download($filePath);
            }
        
            abort(404, 'Archivo no encontrado');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al descargar el archivo. Detalles: ' . $e->getMessage()], 500);
        }
    }
    public function getMessageCount()
    {
        try {
            $userId = Auth::id();
    
            // Contar el total de mensajes no leídos de todos los grupos del usuario
            $messageCount = GroupUser::where('user_id', $userId)->sum('count_message');
    
            return response()->json(['count' => $messageCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el contador de mensajes: ' . $e->getMessage()], 500);
        }
    }
    
    
    


 
}
