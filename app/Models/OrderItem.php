<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id', 'product_id', 'name', 'price', 'amount', 'status'
    ];
    protected $casts = [
        'created_at' => 'string:Y-m-d H:i:s',
        'updated_at' => 'string:Y-m-d H:i:s'
    ];
}
