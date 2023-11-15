<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportesCotizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reportes_cotizacion';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Encabezado',
        'Simbologia',
        'Texto',
        'Texto_firma_cliente',
        'Despedida',
        'Titulo_responsable',
        'Id_responsable',
        'Num_rev',
        'Fecha_inicio',
        'Fecha_fin',
    ];
}
