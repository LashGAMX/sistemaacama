<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalentamientoMatraz extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'calentamiento_matraces';
    protected $primaryKey = 'Id_calentamiento';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Masa_constante',
        'Temperatura',
        'Entrada',
        'Salida',
        'Id_user_c',
        'Id_user_m',
    ];
}