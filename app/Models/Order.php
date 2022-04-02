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
    const ORDER_STATUS_REFUNDED = -1;
    const ORDER_STATUS_NOT_SHIPPED = 0;
    const ORDER_STATUS_IN_STOCK = 1;
    const  ORDER_STATUS_SOLD_OUT = 2;

    const  ORDER_STATUS_NEED_PURCHASE = 3;
    const ORDER_STATUS_PURCHASING = 4;
    const ORDER_STATUS_SHIPPED = 5;
    const ORDER_STATUS_COMPLETED = 6;

    const ORDER_STATUS_GROUP = [
        self::ORDER_STATUS_REFUNDED => '已退货',
        self::ORDER_STATUS_NOT_SHIPPED => '未发货',
        self::ORDER_STATUS_IN_STOCK => '有货',
        self::ORDER_STATUS_SOLD_OUT => '无货',
        self::ORDER_STATUS_NEED_PURCHASE => '需要采购',
        self::ORDER_STATUS_PURCHASING => '采购中',
        self::ORDER_STATUS_SHIPPED => '已发货',
        self::ORDER_STATUS_COMPLETED => '订单完成'
    ];
    const ORDER_STATUS_TAG_GROUP = [
        self::ORDER_STATUS_REFUNDED => 'danger',
        self::ORDER_STATUS_NOT_SHIPPED => 'danger',
        self::ORDER_STATUS_IN_STOCK => 'success',
        self::ORDER_STATUS_SOLD_OUT => 'info',
        self::ORDER_STATUS_NEED_PURCHASE => 'warning',
        self::ORDER_STATUS_PURCHASING => 'success',
        self::ORDER_STATUS_SHIPPED => 'success',
        self::ORDER_STATUS_COMPLETED => 'success',
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
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
