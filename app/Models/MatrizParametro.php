<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizParametro extends Model
{
    use HasFactory;
    protected $table = 'matriz_parametros';
    protected $primaryKey = 'Id_matriz_parametro';
    public $timestamps = true;

    protected $fillable = [
        'Matriz',
        'Status'
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
