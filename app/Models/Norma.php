<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Norma extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'normas';
    protected $primaryKey = 'Id_norma';
    public $timestamps = true;

    protected $fillable = [
        'Norma',
        'Clave_norma',
        'Id_descarga',
        'Id_tipo',
        'Inicio_validez',
        'Fin_validez',
        'Id_user_c',
        'Id_user_m'
    ];
}
