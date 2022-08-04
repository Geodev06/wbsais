<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'items',
        'amount',
        'customer_amount',
        'user_id',
    ];
}
