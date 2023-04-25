<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;    
    
    public $fillable = [
        'product_name', 
        'price', 
        'in_stock',
        'Id_user_c',
        'Id_user_m'
    ];
}
