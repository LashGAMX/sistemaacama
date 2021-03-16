<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $primaryKey = 'Id_cliente';
    public $timestamps = true;

    protected $fillable = [
        'Nombres',
        'A_paterno',
        'A_materno',
        'RFC',
        'Id_tipo_cliente',
        'deleted_at'
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
