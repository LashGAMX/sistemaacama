<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleVidrio extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_vidrio';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote', 
        'Id_analisis',
        'Id_codigo',
        'Id_parametro', 
        'Id_control',
        'Vidrio1',
        'Vidrio2',
        'Vidrio3',
        'Vidrio4',
        'Vidrio5',
        'Vidrio6',
        'Observacion',
        'Liberado',
        'Cancelado',
        'Analizo',
    ];
}
