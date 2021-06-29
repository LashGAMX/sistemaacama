<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ConductividadMuestra extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'conductividad_muestra';
    protected $primaryKey = 'Id_conductividad';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Conductividad1',
        'Conductividad2',
        'Conductividad3',
        'Promedio',
        
    ];
}
