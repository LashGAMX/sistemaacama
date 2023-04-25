<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteGeneral extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'Id_user_c',
        'Id_user_m',
    ];
}
