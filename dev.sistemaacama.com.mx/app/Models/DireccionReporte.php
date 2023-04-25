<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DireccionReporte extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'direccion_reporte';
    protected $primaryKey = 'Id_direccion';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Direccion',
        'Id_user_c',
        'Id_user_m'
    ];
}
