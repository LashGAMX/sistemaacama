<?php

namespace App\Observers;

use App\Models\RecepcionAlimentos;
use Carbon\Carbon;
use App\Models\Notificacion;
use App\Models\ProcesoAnalisisA;
use Illuminate\Support\Facades\Log;


class NotificacionRetardoMuestra
{
    /**
     * Handle the RecepcionAlimentos "created" event.
     *
     * @param  \App\Models\RecepcionAlimentos  $recepcionAlimentos
     * @return void
     */
     public function created(RecepcionAlimentos $recepcionAlimentos)
    {
        // Convertimos la fecha del registro a formato Carbon
        $fechaRegistro = Carbon::parse($recepcionAlimentos->Fecha); 
        $ahora = Carbon::now();
        $proceso = ProcesoAnalisisA::where('Id_solicitud', $recepcionAlimentos->Id_sol)->first();

        if (!$proceso) {
            // Log::warning(" No se encontró proceso asociado al Id_solicitud {$recepcionAlimentos->Id_sol}");
            return;
        }
        if ($ahora->greaterThan($fechaRegistro)) {
            $mensaje = "No se ha entregado la Muestra {$recepcionAlimentos->Folio}. Al ÁREA DE ALIMENTOS";
            Notificacion::create([
                'Mensaje' => $mensaje,
                'Id_user' => $proceso->Id_recibio,
                'Leido' => 2,
            ]);
           // Log::info(" Notificación creada para usuario {$proceso->Id_recibio}: {$mensaje}");
        }
    }

    /**
     * Handle the ProcesoAnalisisA "updated" event.
     *
     * @param  \App\Models\RecepcionAlimentos  $recepcionAlimentos
     * @return void
     */
      public function updated(RecepcionAlimentos $recepcionAlimentos)
      {
          // Solo ejecuta si el campo 'Entrega' se cambió a 1
          if ($recepcionAlimentos->Entrega == 1) {
      
              // Obtenemos el folio directamente del modelo
              $folio = $recepcionAlimentos->Folio;
      
              // Buscamos una notificación cuyo mensaje contenga exactamente ese folio
              $notificacion = Notificacion::where('Mensaje', 'like', "%{$folio}%")->first();
      
              if ($notificacion) {
                  // Actualizamos el estado de la notificación
                  $notificacion->update(['Leido' => 1]);
                  // Log::info("Notificación del folio {$folio} marcada como leída.");
              }
          }else {
              $folio = $recepcionAlimentos->Folio;
      
              $notificacion = Notificacion::where('Mensaje', 'like', "%{$folio}%")->first();
      
              if ($notificacion) {
                  $notificacion->update(['Leido' => 2]);
                 
              }
          }

      }



    /**
     * Handle the ProcesoAnalisisA "deleted" event.
     *
     * @param  \App\Models\RecepcionAlimentos  $recepcionAlimentos
     * @return void
     */
    public function deleted(RecepcionAlimentos $recepcionAlimentos)
    {
        //
    }

    /**
     * Handle the ProcesoAnalisisA "restored" event.
     *
     * @param  \App\Models\RecepcionAlimentos  $recepcionAlimentos
     * @return void
     */
    public function restored(RecepcionAlimentos $recepcionAlimentos)
    {
        //
    }

    /**
     * Handle the ProcesoAnalisisA "force deleted" event.
     *
     * @param  \App\Models\RecepcionAlimentos  $recepcionAlimentos
     * @return void
     */
    public function forceDeleted(RecepcionAlimentos $recepcionAlimentos)
    {
        //
    }
}
