<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    const PURCHASE_STATUS_COMPLETED = 1;
    const PURCHASE_STATUS_UNDONE = 0;

    const PURCHASE_STATUS_GROUP = [
        self::PURCHASE_STATUS_UNDONE => '未完成',
        self::PURCHASE_STATUS_COMPLETED => '已完成'
    ];
    const  PURCHASE_STATUS_TAG_GROUP = [
        self::PURCHASE_STATUS_UNDONE => 'danger',
        self::PURCHASE_STATUS_COMPLETED => 'success'
    ];
    protected $casts = [
        'created_at' => 'string:Y-m-d H:i:s',
        'updated_at' => 'string:Y-m-d H:i:s'
    ];
    protected $fillable = [
        'product_id', 'storehouse_id', 'amount', 'price', 'total', 'remark', 'item_id'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function storehouse()
    {
        return $this->belongsTo(Storehouse::class, 'storehouse_id', 'id');
    }
}
