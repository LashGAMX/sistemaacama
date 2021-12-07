<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limite003 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'limitepnorma_003';
    protected $primaryKey = 'Id_nmx03';
    public $timestamps = true;

    protected $fillable = [
        'Serv_indirecto',
        'Serv_directo',        
        'Id_user_c',
        'Id_user_m'
    ];
}
