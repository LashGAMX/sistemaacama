<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleDqo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_dqo';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Tipo',
        'Tecnica',
        'Soluble',
        'Titulo_muestra',
        'Molaridad',
        'Titulo_blanco',
        'Equivalencia',
        'Vol_muestra',
        'Blanco',
        'Abs_prom',
        'Abs1',
        'Abs2',
        'Abs3',
        'Factor_dilucion',
        'Observacion',
        'Liberado',
        'Cancelado',
        'Analizo',
    ];
}
