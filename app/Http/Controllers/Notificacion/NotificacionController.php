<?php

namespace App\Http\Controllers\Notificacion;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupUser;

use App\Models\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacion;
use App\Observers\CodigoParametrosObserver;
use App\Observers\LoteDetalleDboObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificacionController extends Controller
{
    public function obtenerYMarcarLeidas(Request $request)
    {
        //$fechaHoy = Carbon::now()->toDateString();
        $userId = Auth::id();
        
        try {
            DB::beginTransaction();
            
            $notificaciones = Notificacion::where('Id_user', $userId)->where('Leido', 0)->latest()->get();
            // Marcar todas las notificaciones obtenidas como leídas
            Notificacion::where('Id_user', $userId)->where('Leido', 0)->update(['Leido' => 1]);
            // foreach ($notificaciones as $item) {
            //     $temp = Notificacion::where('Id_user',$userId);
            //     $temp->Leido = 1;
            //     $temp->save();    
            // }
    
            DB::commit();
             return response()->json($notificaciones);
    
        } catch (\Exception $e) 
        {
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
 

    // AQUI EMPIEZAN LOS METODOS DEL CHAT 



   
}
