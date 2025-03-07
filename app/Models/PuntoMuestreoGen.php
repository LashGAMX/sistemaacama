<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PuntoMuestreoGen extends Model
{    
    use HasFactory,SoftDeletes;
    protected $table = 'puntos_muestreogen';
    protected $primaryKey = 'Id_punto';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Punto_muestreo',
        'Id_user_c',
        'Id_user_m',
        'deleted_at'
    ];
}
