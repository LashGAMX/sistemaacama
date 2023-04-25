<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Frecuencia001 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'frecuencia001';
    protected $primaryKey = 'Id_frecuencia';
    public $timestamps = true;

    protected $fillable = [
        'Descripcion',
        'Tomas',        
        'Id_user_c',
        'Id_user_m'
    ];
}
