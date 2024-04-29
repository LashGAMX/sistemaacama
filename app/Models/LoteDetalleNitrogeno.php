<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleNitrogeno extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_nitrogeno';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Titulado_muestra',
        'Titulado_blanco',
        'Molaridad',
        'Factor_equivalencia',
        'Factor_conversion',
        'Vol_muestra',
        'Resultado',
        'Observacion',
        'Liberado',
        'Cancelado',
        'Analizo',
    ];
}
