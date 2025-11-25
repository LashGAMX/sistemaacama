<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LoteDetalleDirectosA extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'lote_detalle_directosa';
    protected $primaryKey = 'Id_detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_analisis',
        'Id_codigo',
        'Id_parametro',
        'Id_control',
        'Lectura1',
        'Lectura2',
        'Lectura3',
        'Promedio',
        'Temperatura',
        'Color_a',
        'Color_v',
        'Factor_dilucion',
        'Vol_muestra',
        'Ph',
        'Factor_correcion',
        'Resultado',
        'Observacion',
        'Liberado',
        'Cancelado',
        'Analizo',
    ]; 

   public function proceso()
    {
        return $this->belongsTo(ProcesoAnalisisA::class, 'Id_analisis', 'Id_solicitud');
    }

    public function codigo()
    {
        return $this->belongsTo(CodigoParametroA::class, 'Id_codigo', 'Id_codigo');
    }
  
}

