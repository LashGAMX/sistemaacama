<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleGA extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_ga';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_parametro',
        'Id_control',
        'Id_matraz',
        'Matraz',
        'M_final',
        'M_inicial1',
        'M_inicial2',
        'M_inicial3',
        'Ph',
        'Vol_muestra',
        'Blanco',
        'F_conversion',
        'Resultado',
        'Observacion', 
    ];
}
