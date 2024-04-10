<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImpresionInformeMensual extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'impresion_informe_mensual';
    protected $primaryKey = 'Id_reporte';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Id_solicitud2', 
        'Encabezado',
        'Simbologia',
        'Texto',
        'Nota_siralab',
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