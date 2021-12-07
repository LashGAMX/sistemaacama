<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MetodoAforo extends Model
{
    
    use HasFactory,SoftDeletes;

    protected $table = 'metodo_aforo';
    protected $primaryKey = 'Id_aforo';
    public $timestamps = true;

    protected $fillable = [
        'Aforo',
        'Id_user_c',
        'Id_user_m'
    ];
}
