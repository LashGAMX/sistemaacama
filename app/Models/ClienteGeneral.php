<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteGeneral extends Model
{
    use HasFactory;
    protected $table = 'clientes_general';
    protected $primaryKey = 'Id_cliente_general';
    public $timestamps = true;

    protected $fillable = [
        'Id_cliente',
        'Nombre', 
        'Empresa',
        'Alias',
        'Id_intermediario',
        'Id_cliente_siralab',
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
