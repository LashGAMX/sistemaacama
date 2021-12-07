<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelModulo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'nivel_modulos';
    protected $primaryKey = 'Id_nivel_modulos';
    public $timestamps = true;

    protected $fillable = [
        'modulo_id',
        'niveles_id',        
        'status_checkbox',
        'Status',
        'Id_user_c',
        'Id_user_m'
    ];
}
