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
        'Viaticos',
        'Paqueteria',
        'Adicional',
        'Servicio',
        'Km_extra',
        'Precio_km',
        'Precio_km_extra',
        'Tiempo_entrega',
        'Observacion_interna',
        'Observaion_cotizacion',
        'Folio_servicio',
        'Folio',
        'Fecha_cotizacion',
        'Metodo_pago',
        'Costo_total',
        'Estado_cotizacion', 
        'Creado_por',
        'Actualizado_por'
    ];

}

