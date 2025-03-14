<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TituloConsecionSir extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'titulo_concesion_sir';
    protected $primaryKey = 'Id_titulo';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Titulo',
        'Id_user_c',
        'Id_user_m',
        'deleted_at'
    ];
}
