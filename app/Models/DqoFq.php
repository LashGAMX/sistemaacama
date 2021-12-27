<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DqoFq extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dqo_fq';
    protected $primaryKey = 'Id_dqo';
    public $timestamps = true;

    protected $fillable = [
        'Id_lote',
        'Inicio',
        'Fin',
        'Invlab',                
        'Id_user_c',
        'Id_user_m'
    ];
}
