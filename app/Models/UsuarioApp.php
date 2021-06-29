<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioApp extends Model
{
    use HasFactory,SoftDeletes; 
    protected $table = 'usuarios_app';
    protected $primaryKey = 'Id_user';
    public $timestamps = true;

    protected $fillable = [
        'Id_muestreador',
        'User',
        'UserPass',
    ];
}
