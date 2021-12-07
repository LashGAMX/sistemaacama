<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Preservacion extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'preservacion';
    protected $primaryKey = 'Id_preservacion';
    public $timestamps = true;

    protected $fillable = [
        'Preservacion',
        'Id_user_c',
        'Id_user_m'
    ];
}
