<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubNorma extends Model
{
    use HasFactory,SoftDeletes;
        
    protected $table = 'sub_normas';
    protected $primaryKey = 'Id_subnorma';
    public $timestamps = true;

    protected $fillable = [
        'Id_norma',
        'Norma',
        'Clave',
        // 'Id_user_c',
        // 'Id_user_m',
    ];
}
