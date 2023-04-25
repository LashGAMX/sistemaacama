<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionHistorico extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cotizacion_historico';
    protected $primaryKey = 'Id_cotizacion_historico';
    public $timestamps = true;

    protected $fillable = [
        'Id_busquedad',
        'Id_intermedio',
        'Id_cliente',
        'Nombre',
        'Direccion',
        'Atencion',
        'Telefono',
        'Correo',
        'Tipo_servicio',
        'Tipo_descarga',
        'Id_norma',
        'Id_subnorma',
        'Frecuencia_muestreo',
        'Tipo_muestra',
        'Promedio',
        'Numero_puntos',
        'Tipo_reporte',
        'Condicion_venta',
        'Metodo_pago',
        'Tiempo_entrega',
        'Costo_total',
        'Supervicion',
        'Folio_servicio',
        'Cotizacion_folio',
        'Fecha_cotizacion',
        'Fecha_modificacion',
        'Hora_modificacion',
        'Autor'
    ];
}
