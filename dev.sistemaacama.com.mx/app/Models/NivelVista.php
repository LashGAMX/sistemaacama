<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelVista extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'niveles_vista';
    protected $primaryKey = 'Id_nivel_vista';
    public $timestamps = true;

    protected $fillable = [
        'Id_usuario_nivel',
        'Id_modulo',        
        'Id_user_c',
        'Id_user_m'
    ];
}
