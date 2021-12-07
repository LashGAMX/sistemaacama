<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrecioIntermedio extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'precio_intermediario';
    protected $primaryKey = 'Id_precio';
    public $timestamps = true;

    protected $fillable = [
        'Id_intermediario',
        'Tipo_precio',
        'Id_catalogo',
        'Precio',
        'Original',
        'Descuento',
        'Id_user_c',
        'Id_user_m'
    ];
}
