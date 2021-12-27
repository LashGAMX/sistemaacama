<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PruebaConfirmativaFq extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prueba_confirmativa_fq';
    protected $primaryKey = 'Id_pruebaConfir';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Medio',
        'Preparacion',
        'Lectura',                
        'Id_user_c',
        'Id_user_m'
    ];
}
