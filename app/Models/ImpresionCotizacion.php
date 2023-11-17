<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImpresionCotizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'impresion_cotizacion';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Id_cotizacion', 
        'Encabezado',
        'Simbologia',
        'Texto',
        'texto_firma_cliente', 
        'Despedida',
        'Titulo_responsable',
        'Id_responsable',
        'Num_rev',
        'Fecha_inicio',
        'Fecha_fin',
        'Version',
        'Fecha_impresion'
    ];
}