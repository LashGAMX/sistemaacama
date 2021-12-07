<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidad extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'unidades';
    protected $primaryKey = 'Id_unidad';
    public $timestamps = true;

    protected $fillable = [
        'Unidad',
        'Descripcion',
        'Id_user_c',
        'Id_user_m',
    ];
}
