<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmodulosSistema extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'submodulos_sistema';
    protected $primaryKey = 'Id_submodulo';
    public $timestamps = true;

    protected $fillable = [
        'Id_modulo',
        'Submodulo',
    ];
}
