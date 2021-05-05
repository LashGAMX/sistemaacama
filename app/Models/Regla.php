<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regla extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'reglas';
    protected $primaryKey = 'Id_regla';
    public $timestamps = true;

    protected $fillable = [
        'Regla',
        'Descripcion',
    ];
}
