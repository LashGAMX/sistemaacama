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
        'Cliente',
        'Folio_servicio',
        'Cotizacion_folio',
        'Empresa',
        'Servicio',
        'Fecha_cotizacion',
        'Supervicion',
        'Created_by',
        'deleted_at',
        'Telefono',
        'Correo',
        'Tipo_descarga',
        'Tipo_servicio',
        'Estado_cotizacion',
        'Puntos_muestreo',
        'Promedio',
        'Tipo_muestra',
        'frecuencia',
        'Norma_formulario_uno',
        'clasificacion_norma',
        'condicciones_venta',
        'Reporte',
        'Viaticos',
        'Paqueteria',
        'Gastos_extras',
        'Numero_servicio',
        'Km_extra',
        'observacionInterna',
        'observacionCotizacion',
        'tarjeta',
        'tiempoEntrega',
        'precioKmExtra',
        'fecha',
        'hora',
        'autor'
    ];
}
