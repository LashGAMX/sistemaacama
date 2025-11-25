<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecepcionAlimentos;
use Carbon\Carbon;



class ProcesoAnalisisA extends Model
{
    use HasFactory;
    protected $table = 'proceso_analisisa';
    protected $primaryKey = 'Id_procAnalisis';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Folio',
        'Cliente',
        'Empresa',
        'Ingreso',
        'Impresion_informe',
        'Id_dirección',
        'Hora_recepcion',
        'Hora_entrada',
        'Fecha_muestreo',
        'Periodo_analisis',
        'Id_recibio',
        'Recibio',
        'Descarga',
        'Obs_ambiental',        
        'Obs_muestreo',
        'created_at',
        'updated_at',
        'deleted_at',
        'Id_user_c' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'Id_user_c', 'id');
    }
  

protected static function booted()
    {
        // Evento before save (create o update) ANTES DE QUE SE CREE 
        // static::saving(function ($modelo) {
        //     if (!empty($modelo->Hora_recepcion)) {
        //         //$modelo->Periodo_analisis = Carbon::parse($modelo->Hora_recepcion)->addDays(4);
        //     }
        // });

        // Evento after create  DESPUES DE QUE SE CREA 
        static::created(function ($proceso) {
            $horaRecepcion = Carbon::parse($proceso->Hora_recepcion);
            $minutosAleatorios = rand(15, 25);
            $horaRecepcionConRango = $horaRecepcion->copy()->addMinutes($minutosAleatorios);

            $muestras = SolicitudMuestraA::where('Id_solicitud', $proceso->Id_solicitud)->get();

            foreach ($muestras as $index => $muestra) {
                if ($muestra && $muestra->Muestra) {
                    RecepcionAlimentos::create([
                        'Id_sol'         => $proceso->Id_solicitud,
                        'Folio'          => $proceso->Folio . '-' . ($index + 1),
                        'Id_muestra'     => $muestra->Id_muestra,
                        'Hora_recepcion' => $proceso->Hora_recepcion,
                        'Fecha_muestreo' => $muestra->Fecha_muestreo,
                        'Muestra'        => $muestra->Muestra,
                        'Recibio'        => $proceso->Recibio,
                        'Fecha'          => $horaRecepcionConRango,
                        'Nombre'         => 'SIN ANALISTA',
                        'Cancelado'      => 0,
                        'Estatus'        => 0, 
                        'Fecha_R_Recep'  => $proceso->created_at,
                    ]);
                }
            }
        });
    }
}
