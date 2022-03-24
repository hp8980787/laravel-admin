<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'country_id', 'attention'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }


    public function  product(){
        return $this->belongsToMany(Product::class,'storehouse_product','storehouse_id','product_id')
            ->withPivot('amount')
            ->withTimestamps();
    }
}
