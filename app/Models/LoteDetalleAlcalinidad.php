<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleAlcalinidad extends Model
{
    use HasFactory,SoftDeletes; 
    protected $table = 'lote_detalle_alcalinidad';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Titulados',
        'Ph_muestra',
        'Vol_muestra',
        'Normalidad',
        'Factor_conversion',
        'Resultado',
        'Observacion',
        'Liberado', 
        'Analizo',
    ];
}