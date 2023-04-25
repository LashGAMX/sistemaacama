<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelModuloPantalla extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'nivel_modulo_pantalla';
    protected $primaryKey = 'Id_nivel_pantalla';
    public $timestamps = true;

    protected $fillable = [
        'Id_Nivel',
        'Id_modulo_pantalla',
        'Id_user_c',
        'Id_user_m'
    ];
}
