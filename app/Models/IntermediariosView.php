<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntermediariosView extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ViewIntermediarios';
    protected $primaryKey = 'Id_intermediario';
    public $timestamps = true;

    protected $fillable = [
        'Id_cliente',
        'Nombres',
        'A_paterno',
        'A_materno',
        'RFC',
        'Id_tipo_cliente',
        'Id_laboratorio',
        'Sucursal',
        'Correo',
        'Direccion',
        'Tel_oficina',
        'Extension',
        'Celular1',
        'Detalle',
        'created_at	',
        'updated_at',
        'deleted_at',
    ];
}
