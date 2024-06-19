<?php

namespace App\Observers;

use App\Models\LoteDetalle;
use App\Models\LoteDetalleDbo;
use App\Models\Notificacion;
use App\Models\Parametro;
use App\Models\Users;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class LoteDetalleDboObserver
{
    /**
     * Handle the LoteDetalleDbo "created" event.
     *
     * @param  \App\Models\LoteDetalleDbo  $loteDetalleDbo
     * @return void
     */
    public function created(LoteDetalleDbo $loteDetalleDbo)
    {
        //
    }

    /**
     * Handle the LoteDetalleDbo "updated" event.
     *
     * @param  \App\Models\LoteDetalleDbo  $loteDetalleDbo
     * @return void
     */
    public function updated(LoteDetalleDbo $loteDetalleDbo)
    {
        $parametro=Parametro::where('Id_parametro',$loteDetalleDbo->Id_parametro)->first();
        $user=Auth::user();

        $viewCodigoParametro = DB::table('ViewCodigoParametro')->where('Id_codigo', $loteDetalleDbo->Id_codigo)->first();
        if($loteDetalleDbo->isDirty('Sugerido_sup') && $loteDetalleDbo->Sugerido_sup==1){
            $mensaje = "{$user->name} Te ha sugerido Liberar el Resultado {$loteDetalleDbo->Resultado} del Parametro: {$parametro->Parametro} con ID: ({$parametro->Id_parametro}) Tipo Formula:{$viewCodigoParametro->Tipo_formula}  de la Muestra: {$viewCodigoParametro->Codigo}";
            Notificacion::create([
                'Mensaje' => $mensaje,
                'Id_user' => $loteDetalleDbo->Analizo,
            ]);
        }
        
    }

    /**
     * Handle the LoteDetalleDbo "deleted" event.
     *
     * @param  \App\Models\LoteDetalleDbo  $loteDetalleDbo
     * @return void
     */
    public function deleted(LoteDetalleDbo $loteDetalleDbo)
    {
        //
    }

    /**
     * Handle the LoteDetalleDbo "restored" event.
     *
     * @param  \App\Models\LoteDetalleDbo  $loteDetalleDbo
     * @return void
     */
    public function restored(LoteDetalleDbo $loteDetalleDbo)
    {
        //
    }

    /**
     * Handle the LoteDetalleDbo "force deleted" event.
     *
     * @param  \App\Models\LoteDetalleDbo  $loteDetalleDbo
     * @return void
     */
    public function forceDeleted(LoteDetalleDbo $loteDetalleDbo)
    {
        //
    }
}
