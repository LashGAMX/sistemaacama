<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleDureza extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_dureza';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Resultado',
        'Edta',
        'Blanco',
        'Vol_muestra',
        'Factor_real',
        'Factor_conversion',
        'Observacion',
        'Liberado',
        'Analizo',
    ];
}
