<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizaciones extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cotizacion';
    protected $primaryKey = 'Id_cotizacion';
    public $timestamps = true;

    protected $fillable = [
        'Cliente',
        'Folio_servicio',
        'Cotizacion_folio',
        'Empresa',
        'Servicio',
        'Fecha_cotizacion',
        'Supervicion',
        'Created_by',
        'deleted_at'
    ];
}
