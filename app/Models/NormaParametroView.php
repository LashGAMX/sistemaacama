<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormaParametroView extends Model
{
    use HasFactory;
    protected $table = 'ViewNormaParametro';
    protected $primaryKey = 'Id_norma_param';
    // public $timestamps = true;

    protected $fillable = [
        'Id_norma',
        'Norma',
        'Clave',
        'Id_parametro',
        'Parametro',
        'Id_matriz',
        'Matriz'
    ];
}
