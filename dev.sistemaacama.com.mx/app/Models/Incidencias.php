<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidencias extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'incidencias';
    protected $primaryKey = 'Id_incidencia';
    public $timestamps = true;

    protected $fillable = [
        'Id_prioridad',
        'Id_modulo',
        'Id_submodulo',
        'Id_user',
        'Descripcion',
        'Imagen',
        'Id_estado',
        'Observacion',
    ];
}
