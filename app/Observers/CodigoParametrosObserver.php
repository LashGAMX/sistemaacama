<?php

namespace App\Observers;

use App\Models\CodigoParametros;
use App\Models\Notificacion;
use App\Models\Parametro;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class CodigoParametrosObserver
{
    /**
     * Handle the CodigoParametros "created" event.
     *
     * @param  \App\Models\CodigoParametros  $codigoParametros
     * @return void
     */
    public function created(CodigoParametros $codigoParametros)
    {
        //
    }



    /**
     * Handle the CodigoParametros "updated" event.
     *
     * @param  \App\Models\CodigoParametros  $codigoParametros
     * @return void
     */
   
    public function updated(CodigoParametros $codigoParametros)
    {
        $parametro=Parametro::where('Id_parametro',$codigoParametros->Id_parametro)->first();
        //$user = Users::where('id',$codigoParametros->Analizo)->first();
        $user = Auth::user();
        if ($codigoParametros->isDirty('Liberado') && $codigoParametros->Liberado == 0) {
            $mensaje = "{$user->name} te han Regresado el parametro {$parametro->Parametro} Con ID:({$parametro->Id_parametro}) del Folio {$codigoParametros->Codigo}";
            Notificacion::create([
                'Mensaje' => $mensaje,
                'Id_user' => $codigoParametros->Analizo,
            ]);
        }
        else if ($codigoParametros->isDirty('Asignado') && $codigoParametros->Asignado == 0) {
            $mensaje = "{$user->name} Ha regresado al cuadro de asignacion el Parametro: {$parametro->Parametro} Con ID:({$parametro->Id_parametro}) del Folio {$codigoParametros->Codigo} ";
            Notificacion::create([
                'Mensaje' => $mensaje,
                'Id_user' => $codigoParametros->Analizo,
            ]);
        }

    }

    /**
     * Handle the CodigoParametros "deleted" event.
     *
     * @param  \App\Models\CodigoParametros  $codigoParametros
     * @return void
     */
    public function deleted(CodigoParametros $codigoParametros)
    {
        //
    }

    /**
     * Handle the CodigoParametros "restored" event.
     *
     * @param  \App\Models\CodigoParametros  $codigoParametros
     * @return void
     */
    public function restored(CodigoParametros $codigoParametros)
    {
        //
    }

    /**
     * Handle the CodigoParametros "force deleted" event.
     *
     * @param  \App\Models\CodigoParametros  $codigoParametros
     * @return void
     */
    public function forceDeleted(CodigoParametros $codigoParametros)
    {
        //
    }
}
