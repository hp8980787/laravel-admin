<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

//    protected $connection = 'order';
    protected $table = 'gross_order';
    protected $casts = [
        'info' => 'json',
        'created_at' => 'date:Y-m-d H:i:s'
    ];



    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'id');
    }

    public function ScopeMinTime($query, $time = null)
    {
        return $query->where('created_at', '>=', $time);
    }

    protected static function booted()
    {
//        static::addGlobalScope('record_status', function (Builder $builder) {
//            $builder->where('record_status',  1);
//        });
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class,'order_id','id');
    }
}
