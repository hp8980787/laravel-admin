<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;

    protected $table = 'ship';
    protected $fillable = [
        'order_id', 'no', 'price', 'completed_at', 'express_company',
    ];
}
