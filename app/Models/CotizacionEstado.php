<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionEstado extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cotizacion_estado';
    protected $primaryKey = 'Id_estado';
    public $timestamps = true;

    protected $fillable = [
        'Estado',
        'Descripcion',
        'Id_user_c',
        'Id_user_m'
    ];
}
