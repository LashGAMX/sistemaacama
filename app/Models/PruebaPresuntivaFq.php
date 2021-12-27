<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PruebaPresuntivaFq extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prueba_presuntiva_fq';
    protected $primaryKey = 'Id_pruebaPresun';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Preparacion',
        'Lectura',                
        'Id_user_c',
        'Id_user_m'
    ];
}
