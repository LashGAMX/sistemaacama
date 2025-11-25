<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
class RecepcionAlimentos extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'recepcion_laboratorioali';
    protected $primaryKey = 'Id_rep';
    public $timestamps = true;

    protected $fillable = [
        'Id_muestra',
        'Id_sol',
        'Folio',
        'Muestra',
        'Fecha',
        'Fecha_R_Recep',
        'Fecha_R_Alimento',
        'AnalistaRes',
        'AnalistaRecep',
        'Id_user',
        'Hora_recepcion',
        'Recibio',
        'Fecha_inicio',
        'Fecha_resguardo',
        'Resguardo',
        'Resguardo2',
        'Fecha_desecho',
        'Analista_desecho',
        'Estatus',
        'Entrega',
        'Cancelado',
        'Lugar_desecho',
        'Fecha_muestreo',
        'Horas',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->Fecha_muestreo && $model->Fecha_inicio) {
                $fechaMuestreo = Carbon::parse($model->Fecha_muestreo);
                $fechaResguardo = Carbon::parse($model->Fecha_inicio);
                $diff = $fechaMuestreo->diff($fechaResguardo);
                // Guardamos como formato HH:MM:SS
                $model->Horas = sprintf('%02d:%02d:%02d', $diff->h + ($diff->days * 24), $diff->i, $diff->s);
            } else {
                $model->Horas = "N/A";
            }
        });
        
    }
}

