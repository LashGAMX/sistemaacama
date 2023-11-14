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
        'EdtaVal1',
        'Blanco',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Ph_muestraVal1',
        'Vol_muestraVal1',
        'Factor_realVal1',
        'Factor_conversionVal1',
        'ResultadoVal1',
        'EdtaVal2',
        'Ph_muestraVal2',
        'Vol_muestraVal2',
        'Factor_realVal2',
        'Factor_conversionVal2',
        'ResultadoVal2',
        'EdtaVal3',
        'Ph_muestraVal3',
        'Vol_muestraVal3',
        'Factor_realVal3', 
        'Factor_conversionVal3',
        'ResultadoVal3',
        'Observacion',
        'Liberado',
        'Analizo',
    ];
}
