<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientesNoReg extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clientes_no_reg';
    protected $primaryKey = 'Id_cliente_no_reg';
    public $timestamps = true;

    protected $fillable = [
        'No_folio',
        'Nombres', 
        'A_paterno',
        'A_materno',
        'RFC',
        'Empresa_cliente',
        'Id_user_c',
        'Id_user_m',
    ];
}
