<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'order';
    protected $table = 'gross_order';
    protected $casts=[
        'info'=>'json',
        'created_at'=>'string:Y-m-d H:i:s'
    ];
    public function domain()
    {
        return $this->belongsTo(Domain::class,'domain_id','id');
    }
}
