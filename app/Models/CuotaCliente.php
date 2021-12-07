<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuotaCliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cuotas_cliente';
    protected $primaryKey = 'Id_cuota_cliente';
    public $timestamps = true;

    protected $fillable = [
        'Id_cuota',
        'Id_cliente',
        'Id_nivel_cli',
        'Id_user_c',
        'Id_user_m'
    ];
}
