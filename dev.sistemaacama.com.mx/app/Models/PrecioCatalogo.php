<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrecioCatalogo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'precio_catalogo';
    protected $primaryKey = 'Id_precio';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro',
        'Id_laboratorio',
        'Precio',
        'Revision',
        'Id_user_c',
        'Id_user_m',
    ];
}
