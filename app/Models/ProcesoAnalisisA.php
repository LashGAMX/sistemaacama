<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'Id_recibio',
        'Recibio',
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
        // Evento para manejar la creación del modelo
        static::created(function ($procesoAnalisis) {
            $horaRecepcion = Carbon::parse($procesoAnalisis->Hora_recepcion); 
            
            // Generar un número aleatorio entre 15 y 25
            $minutosAleatorios = rand(15, 25);
    
            // Sumar los minutos aleatorios a la hora de recepción
            $horaRecepcionConRango = $horaRecepcion->addMinutes($minutosAleatorios);
    
            RecepcionAlimentos::create([
                'Id_rep' => $procesoAnalisis->Id_procAnalisis,
                'Folio' => $procesoAnalisis->Folio,
                'Hora_recepcion' => $procesoAnalisis->Hora_recepcion,
                'Recibio' => $procesoAnalisis->Recibio,
                'Fecha' => $horaRecepcionConRango, 
                'Nombre' => 'Sin Analista', 
            ]);
        });
    
        // Evento para manejar la actualización del modelo
        static::updated(function ($procesoAnalisis) {
            // Verificar si las columnas específicas se actualizaron
            if ($procesoAnalisis->isDirty(['Hora_recepcion', 'Recibio'])) {
                $horaRecepcion = Carbon::parse($procesoAnalisis->Hora_recepcion);
    
                // Generar un número aleatorio entre 15 y 25
                $minutosAleatorios = rand(15, 25);
    
                // Sumar los minutos aleatorios a la hora de recepción
                $horaRecepcionConRango = $horaRecepcion->addMinutes($minutosAleatorios);
    
                // Buscar el registro correspondiente en el modelo RecepcionAlimentos
                $recepcionAlimentos = RecepcionAlimentos::where('Id_rep', $procesoAnalisis->Id_procAnalisis)->first();
    
                if ($recepcionAlimentos) {
                    // Actualizar los campos necesarios
                    $recepcionAlimentos->update([
                        'Hora_recepcion' => $procesoAnalisis->Hora_recepcion,
                        'Recibio' => $procesoAnalisis->Recibio,
                        'Fecha' => $horaRecepcionConRango,
                    ]);
                }
            }
        });
    }
    

    
  

}
