<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModulosSistema extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'modulos_sistema';
    protected $primaryKey = 'Id_modulos';
    public $timestamps = true;

    protected $fillable = [
        'Modulo',
    ];
}
