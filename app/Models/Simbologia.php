<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simbologia extends Model
{
    use HasFactory;
    
    protected $table = 'simbologias';
    protected $primaryKey = 'Id_simbologia';
    public $timestamps = true;

    protected $fillable = [
        'Simbologia',
        'Descripcion',
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
 