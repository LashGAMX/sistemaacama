<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampoGenerales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'campo_generales';
    protected $primaryKey = 'Id_general';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Captura',
        'Id_equipo',
        'Temperatura_a',
        'Temperatura_b',
        'Latitud',
        'Longitud',
        'Altitud',
        'Pendiente',
        'Criterio'
    ];
}
