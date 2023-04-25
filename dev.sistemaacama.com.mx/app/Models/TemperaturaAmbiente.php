<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TemperaturaAmbiente extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'temperatura_ambiente';
    protected $primaryKey = 'Id_temperatura';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Num_toma',
        'TemperaturaSin1', 
        'Temperatura1',
        'TemperaturaSin2',
        'Temperatura2',
        'TemperaturaSin3',
        'Temperatura3',
        'Promedio',
        'Fact_apl',
        'Activo',
        'Id_user_c',
        'Id_user_m'
    ];
}
