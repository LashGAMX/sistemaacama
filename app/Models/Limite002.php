<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limite002 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'limitepnorma_002';
    protected $primaryKey = 'Id_nmx02';
    public $timestamps = true;

    protected $fillable = [
        'Prom_intsmax',
        'Prom_intsmin',        
        'Id_user_c',
        'Id_user_m'
    ];
}
