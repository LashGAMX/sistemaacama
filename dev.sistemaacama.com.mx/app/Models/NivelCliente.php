<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelCliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'nivel_clientes';
    protected $primaryKey = 'Id_nivel';
    public $timestamps = true;

    protected $fillable = [
        'Nivel',
        'Descuento',        
        'Id_user_c',
        'Id_user_m'
    ];
}
