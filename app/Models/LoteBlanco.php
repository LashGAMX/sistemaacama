<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoteBlanco extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'lote_blanco';
    protected $primaryKey = 'Id_blanco';
    public $timestamps = true;

    protected $fillable = [
        'Abs_teorico',
        'Abs1',
        'Abs2',
        'Abs3',
        'Abs4',
        'Abs5',
        'Abs_promedio',        
        'Id_user_c',
        'Id_user_m'
    ];
}
