<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformesRelacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'informes_relacion';
    protected $primaryKey = 'Id_relacion';
    public $timestamps = true;

    protected $fillable = [
        'Id_cotizacion',
        'Id_solicitud',
        'Id_solicitud2',
        'Id_solicitud',
        'Tipo',
        'Id_reporte',
       
    ];
}
