<?php

namespace App\Http\Controllers\Notificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Observers\CodigoParametrosObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class NotificacionController extends Controller
{
    public function obtenerYMarcarLeidas(Request $request)
{
    $userId = Auth::id();
    
    try {
        DB::beginTransaction();
        
        $notificaciones = Notificacion::where('Id_user', $userId)->where('Leido', 0)->latest()->get();
        
        // Marcar todas las notificaciones obtenidas como leídas
        Notificacion::where('Id_user', $userId)->where('Leido', 0)->update(['Leido' => 1]);
        
        DB::commit();
        
        return response()->json($notificaciones);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => 'Error al obtener y marcar notificaciones como leídas'], 500);
    }
}
    public function ContNot()
    {
        $userId = Auth::id();
        $contador = Notificacion::where('Id_user', $userId)->where('Leido','=',0)->count();
        return response()->json($contador);
    }

    public function verNotificaciones()
    {
        $userId = Auth::id();
        $notificaciones = Notificacion::where('Id_user', $userId)->orderBy('Id_notificacion', 'desc')->get();
     
        return view('notificacion.VerNot',compact('notificaciones'));
    }
 
    
   
}
