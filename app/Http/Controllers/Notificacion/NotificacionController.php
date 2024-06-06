<?php

namespace App\Http\Controllers\Notificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Observers\CodigoParametrosObserver;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function obtenerNotificaciones()
    {
        $userId = Auth::id();
        $notificaciones = Notificacion::where('Id_user', $userId)->where('Leido','=',0)->latest()->take(10)->get();
        return response()->json($notificaciones);
    }
    public function Marcarleido(Request $request)
    {
        $Id = $request->input('Id_notificacion');
        try {
            $notificacion = Notificacion::findOrFail($Id); 
            $notificacion->Leido = 1;
            $notificacion->save();
            return response()->json($notificacion);
        } catch (\Exception $e) {
            return response()->json(['error' => 'La notificación no fue encontrada'], 404);
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

        return view('notificacion.VerNot', compact('notificaciones'));
    }
   
}
