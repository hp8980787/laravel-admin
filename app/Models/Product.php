<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'id';

    public function stocks()
    {
        return $this->belongsToMany(Storehouse::class, 'storehouse_product', 'product_id', 'storehouse_id')
            ->withPivot('amount')
            ->withTimestamps();
    }


}
