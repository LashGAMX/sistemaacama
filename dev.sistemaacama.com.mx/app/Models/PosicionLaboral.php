<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosicionLaboral extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'posiciones_laborales';
    protected $primaryKey = 'Id_posiciones_laborales';
    public $timestamps = true;

    protected $fillable = [
        'Nombre_vacante',                
        'Id_user_c',
        'Id_user_m',
        'Status'
    ];
}
