<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadenaGenerales extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'cadena_generales';
    protected $primaryKey = 'Id_cadena';
    public $timestamps = true;

    protected $fillable = [
        'Id_solicitud',
        'Area',
        'Responsable',
        'Recipientes',
        'Fecha_salida',
        'Fecha_entrada',
        'Fecha_salidaEli',
        'Fecha_emision',
        'Firma',
        'User',
    ];
}
