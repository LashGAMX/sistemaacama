<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionNitrogeno extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'valoracion_nitrogeno';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_parametro',
        'Tipo',
        'Blanco',
        'Gramos',
         'Factor_conversion',
         'Titulo1',
         'Titulo2',
         'Titulo3',
         'Pm',
        'Resultado',
    ];
}
