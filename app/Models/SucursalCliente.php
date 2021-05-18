<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SucursalCliente extends Model
{
    use HasFactory,SoftDeletes;
        
    protected $table = 'sucursales_cliente';
    protected $primaryKey = 'Id_sucursal'; 
    public $timestamps = true;

    protected $fillable = [
        'Id_cliente',
        'Empresa',
        'Estado',
        'Id_siralab',
        //  'Id_user_c',
        // 'Id_user_m',
    ];
}
