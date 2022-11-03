<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleEcoli extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lote_detalle_Ecoli';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Tipo',
        'Positivos',
        'Colonia1',
        'Colonia2',
        'Colonia3',
        'Colonia4',
        'Colonia5',
        'NMP',
        'Resultado',
        'Observacion',
        'Liberado',
        'Analizo',
    ];
}
