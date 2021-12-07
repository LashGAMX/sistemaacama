<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoCuerpo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tipo_cuerpo';
    protected $primaryKey = 'Id_tipo';
    public $timestamps = true;

    protected $fillable = [
        'Cuerpo',        
        'Id_user_c',
        'Id_user_m'
    ];
}
