<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use HasFactory,SoftDeletes;
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
        // 'Id_user_m',
    ];
}
