<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionMuestreo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cotizacion_muestreos';
    protected $primaryKey = 'Id_muestreo';
    public $timestamps = true;

    protected $fillable = [
        'Id_cotizacion',
        'Dias_hospedaje',
        'Hospedaje',
        'Dias_muestreo', 
        'Num_muestreo',
        'Caseta',
        'Km',
        'Km_extra',
        'Gasolina_teorico',
        'Cantidad_gasolina',
        'Paqueteria',
        'Adicional',
        'Num_servicio',
        'Num_muestreador',
        'Estado',
        'Localidad',
        'Total',
    ]; 
}
