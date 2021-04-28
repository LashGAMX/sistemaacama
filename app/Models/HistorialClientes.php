<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialClientes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hist_clientes';
    protected $primaryKey = 'Id_hist';
    public $timestamps = true;

    protected $fillable = [
        'Id_cliente',
        'Nombres',
        'A_paterno',
        'A_materno',
        'RFC',
        'Nota',
        'F_creacion',
        'Id_user_c',
        'F_modificacion',
        'Id_user_m', 
    ];
}
