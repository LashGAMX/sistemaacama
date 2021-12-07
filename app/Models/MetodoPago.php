<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodoPago extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'metodo_pago';
    protected $primaryKey = 'Id_metodo';
    public $timestamps = true;

    protected $fillable = [
        'Metodo',
        'Descripcion',        
        'Id_user_c',
        'Id_user_m'
    ];
}
