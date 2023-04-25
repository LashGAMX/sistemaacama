<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipios extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'municipios';
    protected $primaryKey = 'Id_municipio';
    public $timestamps = true;

    protected $fillable = [
        'Id_estado',
        'Clave',
        'Nombre',
        'Status',        
        'Id_user_c',
        'Id_user_m'
    ];
}
