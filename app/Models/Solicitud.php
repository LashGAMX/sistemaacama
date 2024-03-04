<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'solicitudes';
    protected $primaryKey = 'Id_solicitud';
    public $timestamps = true;

    protected $fillable = [
      'Id_cotizacion',
      'Folio',
      'Folio_servicio',
      'Id_intermediario',
      'Id_cliente',
      'Siralab',
      'Id_sucursal',
      'Id_direccion',
      'Id_contacto',
      'Atencion',
      'Observacion',
      'Observacion_plan',
      'Id_servicio',
      'Id_descarga',
      'Id_norma',
      'Id_subnorma',
      'Fecha_muestreo',
      'Id_muestreo',
      'Num_tomas',
      'Id_muestra',
      'Id_promedio',
      'Id_reporte',
      'Id_reporte2',
      'Padre',
      'Hijo',
      'Nota_4',
      'Liberado',
      'Id_user_c',
      'Id_user_m'
    ];
}
