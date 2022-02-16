<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleCloro extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_cloro';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote', 
        'Id_analisis',
        'Id_parametro',
        'Id_control',
        'Vol_muestra',
        'Ml_muestra',
        'Vol_blanco',
        'Normalidad',
        'Observacion',
        'Ph_final',
        'Ph_inicial',
        'Factor_conversion',
        'Resultado',
        
    ];
}
