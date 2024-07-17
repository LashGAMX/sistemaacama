<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class LoteDetalleColor extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_color';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Vol_muestra',
        'Ph_muestra',
        'Fd1',
        'Fd2',
        'Fd3',
        'Longitud1',
        'Longitud2',
        'Longitud3',
        'Abs1_436',
        'Abs2_436',
        'Abs3_436',
        'Abs1_525',
        'Abs2_525',
        'Abs3_525',
        'Abs1_620',
        'Abs2_620',
        'Abs3_620',
        'Abs_promedio1',
        'Abs_promedio2',
        'Abs_promedio3',
        'Resultado1',
        'Resultado2',
        'Resultado3',
        'Observacion1',
        'Observacion2',
        'Observacion3',
        'Liberado',
        'Cancelado',
        'Analizo',
    ];
}

