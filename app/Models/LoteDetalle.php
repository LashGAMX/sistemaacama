<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalle extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Descripcion',
        'Vol_muestra',
        'Abs1',
        'Abs2',
        'Abs3',
        'Abs_promedio',
        'Factor_dilucion',
        'Factor_conversion',
        'Vol_disolucion',
        'Fecha',
        'Liberado',
    ];
}
