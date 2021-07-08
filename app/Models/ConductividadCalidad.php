<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConductividadCalidad extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'conductividad_calidad';
    protected $primaryKey = 'Id_conductividad';
    public $timestamps = true;

    protected $fillable = [
        'Conductividad',
        'Marca',
        'Lote',
        'Inicio_caducidad',
        'Fin_caducidad'
    ];
}
