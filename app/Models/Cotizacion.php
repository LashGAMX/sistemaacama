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
        'Creado_por',
        'Actualizado_por',
        'Hijo',
        'Id_reporte',
        'Tipo',
        'Cancelado',
        'Cotizacion_user',
        'Fecha_impresion',
    ];
 
    public function Cotizacion_estado()
    {
        return $this->belongsTo(CotizacionEstado::class, 'Estado_cotizacion', 'Id_estado');
    }
    
    public function clavenorma()
    {
        return $this->belongsTo(Norma::class, 'Id_norma', 'Id_norma');
    }
    
    public function descarga()
    {
        return $this->belongsTo(TipoDescarga::class, 'Tipo_descarga', 'Id_tipo');
    }
    
    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'Id_cliente', 'Id_cliente');
    }
    
    public function creador()
    {
        return $this->belongsTo(User::class, 'Creado_por', 'id');
    }
    public function actualizado()
    {
        return $this->belongsTo(User::class, 'Actualizado_por', 'id');
    }
    

}

