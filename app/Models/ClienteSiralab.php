<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClienteSiralab extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'clientes_siralab';
    protected $primaryKey = 'Id_cliente_siralab';
    public $timestamps = true;

    protected $fillable = [
        'Id_sucursal',
        'Titulo_concesion',
        'Calle',
        'Num_exterior',
        'Num_interior',
        'Colonia',
        'CP',
        'Ciudad',
        'Localidad',
        'Municipio',
        'Estado'
    ];
}
