<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteDetalleNitrogeno extends Model
{
    use HasFactory;
    protected $table = 'lote_detalle_nitrogeno';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Id_control',
        'Titulado_muestra',
        'Titulado_blanco',
        'Molaridad',
        'Factor_equivalencia',
        'Factor_conversion',
        'Vol_muestra',
        'Resultado',
        'Observacion'
    ];
}
