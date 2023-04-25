<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoMuestra extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tipo_muestra';
    protected $primaryKey = 'Id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'Tipo',        
        'Id_user_c',
        'Id_user_m'
    ];
}
