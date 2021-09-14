<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EstadoAnalisis extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'estado_analisis';
    protected $primaryKey = 'Id_estado';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Fecha_ingreso',
        'Fecha_ingreso2',
        'Fecha_analisis',
        'Proceso'
    ];

}
