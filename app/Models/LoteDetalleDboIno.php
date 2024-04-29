<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleDboIno extends Model
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
        'Vol_muestra',
        'Oxigeno_disueltoini',
        'Oxigeno_disueltofin',
        'Vol_total_frasco',
        'Vol_inoculo',
        'Vol_muestra_siembra',
        'Porcentaje_dilucion',
        'Pre_dilucion',
        'Vol_winker',
        'Botella_od',
        'Botella_fin',
        'Ph_inicial',
        'Ph_final',
        'Resultado',
        'Observacion',
        'Sugerido',
        'Liberado',
        'Cancelado',
        'Analizo',
    ];
}
