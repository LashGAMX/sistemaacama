<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleDbo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_dboino';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Oxigeno_inicial',
        'Oxigeno_final',
        'Oxigeno_blancoIni',
        'Oxigeno_blancoFin',
        'Vol_muestra',
        'Vol_total',
        'Factor_dilucion',
        'Vol_muestraIno',
        'Vol_blancoino',
        'Resultado',
        'Observacion',
        'Sugerido',
        'Liberado',
        'Analizo',
    ];
}
