<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleDbo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_dbo';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Botella_final',
        'Botella_od',
        'Odf',
        'Odi',
        'Ph_final',
        'Ph_inicial',
        'Vol_muestra',
        'Dilucion',
        'Vol_botella',
        'Resultado',
        'Observacion',
        'Sugerido',
        'Liberado',
        'Cancelado',
        'Analizo',
    ];
}
