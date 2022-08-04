<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stash extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'product_name',
        'category',
        'qty',
        'price',
        'user_id'
    ];
}
