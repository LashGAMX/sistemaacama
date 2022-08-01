<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteDetalleSolidos extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_solidos';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote', 
        'Id_analisis',
        'Id_codigo',
        'Id_parametro', 
        'Id_control',
        'Id_crisol',
        'Crisol',
        'Masa1',
        'Masa2',
        'Peso_muestra1',
        'Peso_muestra2',
        'Peso_constante1',
        'Peso_constante2',
        'Vol_muestra',
        'Factor_conversion',
        'Inmhoff',
        'Temp_muestraLlegada',
        'Temp_muestraAnalizada',
        'Resultado',
        'Observacion',
        'Liberado',
        'Analizo',
    ];
}
