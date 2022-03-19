<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
//    protected $connection = 'order';
    protected $table = 'category';
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'category',
        'category_de',
        'category_fr',
        'category_jp',
        'category_nl',
        'category_pt',
        'category_es',

    ];

    public function ScopeCateLike($query, $param)
    {
        if ($param) {
            return $query->orWhere('category', 'like', "%$param%")
                ->orWhere('category_de', 'like', "%$param%")
                ->orWhere('category_fr', 'like', "%$param%")
                ->orWhere('category_jp', 'like', "%$param%")
                ->orWhere('category_nl', 'like', "%$param%")
                ->orWhere('category_pt', 'like', "%$param%")
                ->orWhere('category_es', 'like', "%$param%");

        }
    }
}
