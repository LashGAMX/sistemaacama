<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class MenuUsuarios extends Model
{
    
    use HasFactory;

    protected $table = 'menu_usuarios';
    protected $primaryKey = 'Id_menu';
    public $timestamps = true;

    protected $fillable = [
        'Id_user',
        'Id_item',
    ];
}
