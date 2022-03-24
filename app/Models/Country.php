<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

//    protected $connection = 'order';

    public function ScopeActive($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
    }
}
