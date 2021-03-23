<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioCatalogo extends Model
{
    use HasFactory;
    protected $table = 'precio_catalogo';
    protected $primaryKey = 'Id_precio';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Id_laboratorio',
        'Precio',
    ];
}
