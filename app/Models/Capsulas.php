<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capsulas extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'capsulas';  
    protected $primaryKey = 'Id_capsula'; 
    public $timestamps = true;

    protected $fillable = 
    [
        'Num_serie',
        'Peso',
        'Min',
        'Max',
        'Id_112',
        'Estado',
    ];  

}
