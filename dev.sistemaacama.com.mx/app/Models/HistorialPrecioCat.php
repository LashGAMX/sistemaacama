<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialPrecioCat extends Model
{
    use HasFactory;
    protected $table = 'historial_precioCat';
    protected $primaryKey = 'Id_historial';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Precio',
        'Porcentaje',
        'Anio',
        'Id_laboratorio',
        'Creo',
        'Modifico',
        'F_creacion',
        'F_actualizacion'
    ];
}
