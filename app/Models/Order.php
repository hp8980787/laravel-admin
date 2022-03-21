<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

//    protected $connection = 'order';
//    protected $table = 'gross_order';
    protected $casts = [
        'info' => 'json',
        'created_at' => 'string:Y-m-d H:i:s'
    ];
    protected $fillable = [
        'domain_id', 'trans_id', 'table_name', 'total', 'total_usd', 'currency', 'info', 'ip', 'order_status', 'record_status', 'buy_time'
        ,'pcode','ids'
    ];



    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'id');
    }

    public function ScopeMinTime($query, $time = null)
    {
        return $query->where('created_at', '>=', $time);
    }
}
