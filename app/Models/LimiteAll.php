<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LimiteAll extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'limitepnorma_all';
    protected $primaryKey = 'Id_limite';
    public $timestamps = true;

    protected $fillable = [
        'Id_norma',
        'Id_parametro', 
        'Limite',
    ];
}
