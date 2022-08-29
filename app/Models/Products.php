<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'supplier',
        'category',
        'expiry',
        'qty',
        'price',
        'user_id',
        'inventory_id'
    ];
}
