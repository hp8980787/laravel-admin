<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $imgPath = 'https://www.batteriexpert.com/img/';
    public function stocks()
    {
        return $this->belongsToMany(Storehouse::class, 'storehouse_product', 'product_id', 'storehouse_id')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function getOkpcodeAttribute($value)
    {
        return $this->imgPath . $value . '.jpg';
    }


}
