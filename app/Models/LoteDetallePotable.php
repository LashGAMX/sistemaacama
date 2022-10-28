<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetallePotable extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_potable';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Factor_dilucion',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Vol_muestra',
        'Promedio',
        'Resultado',
        'Observacion',
        'Liberado',
        'Analizo',
    ];
}
