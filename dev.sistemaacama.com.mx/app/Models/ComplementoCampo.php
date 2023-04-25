<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplementoCampo extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'complementos_campo';
    protected $primaryKey = 'Id_complemento';
    public $timestamps = true;

    protected $fillable = [
        'Complemento',
        'Descripcion',
        'Tipo'
    ];
}
