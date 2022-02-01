<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleHH extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_hh';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Id_control',
        'A_alumbricoides',
        'H_nana',
        'Taenia_sp',
        'T_trichiura',
        'Uncinarias',
        'Vol_muestra',
        'Resultado',
        'Observacion'
    ];
}
