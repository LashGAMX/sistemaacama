<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalle extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Id_codigo',
        'Id_control',
        'Descripcion',
        'Vol_muestra',
        'Vol_final',
        'Vol_dirigido',
        'Abs1',
        'Abs2',
        'Abs3',
        'Abs_promedio',
        'Factor_dilucion',
        'Factor_conversion',
        'Resultado_microgramo',
        'Vol_disolucion',
        'Observacion',
        'Ph',
        'Solidos',
        'Olor',
        'Color',
        'Orden',
        'Fecha',
        'Liberado',
        'Analizo',
        'Id_user_c',
        'Id_user_m',
    ];
}
