<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleIcp extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_icp';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [ 
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Parametro',
        'Id_control',
        'Cps', 
        'Dilucion',
        'Resultado',
        'Fecha',
        'Observacion',
        'Liberado',
        'Analizo'
    ];
}
