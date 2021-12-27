<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SembradoFq extends Model
{    
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sembrado_fq';
    protected $primaryKey = 'Id_sembrado';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Sembrado',
        'Fecha_resiembra',
        'Tubo_n',
        'Bitacora',        
        'Id_user_c',
        'Id_user_m'
    ];
}
