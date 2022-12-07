<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limite201 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'limitepnorma_201';
    protected $primaryKey = 'ID_nmx201';
    public $timestamps = true;

    protected $fillable = [
        'Id_parametro', 
        'Per_min',
        'Per_max',
        'Id_user_c',
        'Id_user_m'
    ];
}
