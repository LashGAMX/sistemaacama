<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuota extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cuotas';
    protected $primaryKey = 'Id_cuota';
    public $timestamps = true;

    protected $fillable = [
        'F_creacion',
        'Cuota',
        'Precio',
        'Ap_descuento',
        'Id_tipo_cuota',
        'Status',
        'Id_user_c',
        'Id_user_m'
    ];
}
