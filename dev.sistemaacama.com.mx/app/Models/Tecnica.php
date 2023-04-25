<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tecnica extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'tecnicas';
    protected $primaryKey = 'Id_tecnica';
    public $timestamps = true;

    protected $fillable = [
        'Tecnica',
        //'Status',
        'Id_user_c',
        'Id_user_m',
    ];
}
