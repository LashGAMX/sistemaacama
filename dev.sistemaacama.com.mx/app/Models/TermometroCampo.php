<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermometroCampo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'termometro_campo';
    protected $primaryKey = 'Id_termometro';
    public $timestamps = true;

    protected $fillable = [
        'Id_muestreador',
        'Equipo',
        'Marca',
        'Modelo',
        'Serie',
        'Tipo',
        'Id_user_c',
        'Id_user_m'
    ];
}
