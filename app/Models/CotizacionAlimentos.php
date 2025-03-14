<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CotizacionAlimentos extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Nombre de la tabla
    protected $table = 'cotizacion_alimentos';
    protected $primaryKey = 'Id_cotizacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_intermedio',
        'Id_cliente',
        'Id_sucursal',
        'Nombre',
        'Id_direccion',
        'Id_general',
        'Direccion',
        'Atencion',
        'Telefono',
        'Correo',
        'Tipo_servicio',
        'Tipo_descarga',
        'Id_norma',
        'Id_subnorma',
        'Fecha_muestreo',
        'Frecuencia_muestreo',
        'Tomas',
        'Tipo_muestra',
        'Promedio',
        'Numero_puntos',
        'Tipo_reporte',
        'Tipo_reporte2',
        'Tiempo_entrega',
        'Observacion_interna',
        'Observacion_cotizacion',
        'Folio_servicio',
        'Folio',
        'Fecha_cotizacion',
        'Metodo_pago',
        'Precio_analisis',
        'Precio_catalogo',
        'Paqueteria',
        'Extras',
        'Num_servicios',
        'Descuento',
        'Precio_analisisCon',
        'Precio_muestreo',
        'Iva',
        'Sub_total',
        'Costo_total',
        'Estado_cotizacion',
        'Supervicion',
        'Hijo',
        'Id_reporte',
        'Tipo',
        'Cancelado',
        'Fecha_impresion',
        'Creado_por',
        'Actualizado_por'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'Fecha_muestreo',
        'Fecha_impresion'
    ];
}
