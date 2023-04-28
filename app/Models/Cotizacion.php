<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cotizacion';
    protected $primaryKey = 'Id_cotizacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_intermedio',
        'Id_cliente',
        'Id_sucursal',
        'Id_direccion',
        'Id_general',
        'Nombre',
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
        'Tiempo_entrega',
        'Observacion_interna',
        'Observacion_cotizacion',
        'Folio_servicio',
        'Folio',
        'Fecha_cotizacion',
        'Metodo_pago',
        'Precio_analisis',
        'Precio_catalogo',
        'Descuento',
        'Precio_analisisCon',
        'Precio_muestreo',
        'Iva',
        'Sub_total',
        'Costo_total',
        'Estado_cotizacion', 
        'Creado_por',
        'Actualizado_por',
        'Hijo',
        'Id_reporte',
        'Tipo',
    ];

}

