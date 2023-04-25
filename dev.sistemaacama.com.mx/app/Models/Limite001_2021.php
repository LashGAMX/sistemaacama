<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limite001_2021 extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'limite001_2021';
    protected $primaryKey = 'Id_limite';
    public $timestamps = true;

    protected $fillable = [
        'Id_categoria',
        'Id_parametro',
        'Pm',
        'Pd',
        'Vi',
    ];
}
