<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MetodoAforo extends Model
{
    
    use HasFactory,SoftDeletes;

    protected $table = 'menu_usuarios';
    protected $primaryKey = 'Id_menu';
    public $timestamps = true;

    protected $fillable = [
        'Id_user',
        'Id_item',
    ];
}
