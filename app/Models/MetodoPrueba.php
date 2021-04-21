<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPrueba extends Model
{
    use HasFactory;
    protected $table = 'metodo_prueba';
    protected $primaryKey = 'Id_metodo';
    public $timestamps = true;

    protected $fillable = [
        'Metodo_prueba',
        'Clave_metodo',
        //'Status',
        'Id_user_c',
        'Id_user_m',
    ];
}
