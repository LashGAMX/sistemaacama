<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoracionDureza extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'valoracion_dureza';
    protected $primaryKey = 'Id_valoracion';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Id_parametro',
        'Blanco',
        'Solucion',
        'Disolucion1',
        'Disolucion2',
        'Disolucion3',
        'Resultado',
    ];
}
