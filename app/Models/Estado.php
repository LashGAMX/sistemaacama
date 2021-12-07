<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $table = 'estados';
    protected $primaryKey = 'Id_estado';
    public $timestamps = true;
    protected $fillable = [
        'Nombre',
        'Id_user_c',
        'Id_user_m'
    ]; 
}
 