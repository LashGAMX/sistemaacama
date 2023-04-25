<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactoCliente extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'contactos_cliente';
    protected $primaryKey = 'Id_contacto';
    public $timestamps = true;

    protected $fillable = [
        'Id_cliente',
        'Nombres',
        'A_paterno',
        'A_materno',
        'Celular',
        'Telefono',
        'Email',
        'Id_user_c',
        'Id_user_m'
    ];
}
