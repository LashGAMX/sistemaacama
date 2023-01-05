<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListaIncidencias extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'incidencias';
    protected $primaryKey = 'Id_incidencia';
    public $timestamps = true;

    protected $fillable = [
        'Prioridad',
        'Id_modulo',
        'Id_submodulo',
        'Descripcion',
        'Observacion',
        'Id_user'
    ];
}
