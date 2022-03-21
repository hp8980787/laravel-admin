<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrossOrder extends Model
{
    use HasFactory;

    protected $table = 'gross_order';
    protected $casts = [
        'info' => 'json',
        'created_at' => 'string:Y-m-d H:i:s'
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'id');
    }

    public function ScopeMinTime($query, $time = null)
    {
        return $query->where('created_at', '>=', $time);
    }
    public function setInfoAttribute($val)
    {
        if (!is_string($val)) {
            $this->attributes['info'] = json_encode($val);
        }
    }
}
