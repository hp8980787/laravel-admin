<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Resources\OrdersCollection;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()->paginate(16);
        return $this->success(new OrdersCollection($orders));
    }

    public function todaySales()
    {
        $now = now();
        $time = Carbon::create($now->year, $now->month, $now->day, 8, 0, 0);
        $yesterday = $time->copy()->subDay();
        $orders = Order::query()->where('created_at', '>=', $yesterday)->get();
        $mostNumbers = Order::query()->with('domain')
            ->selectRaw('domain_id,count(domain_id)as numbers,max(created_at)as created_at')
            ->where('created_at', '>=', $yesterday)
            ->groupBy('domain_id')
            ->orderBy('numbers','desc')
            ->limit(5)->get();
        $totalUsd = Order::query()->selectRaw('sum(total_usd)as total')
            ->where('created_at', '>=', $yesterday)->first();
        $mostMoney = Order::query()->with('domain')
            ->selectRaw('domain_id,sum(total_usd)as total_usd,max(created_at)as created_at')
            ->where('created_at', '>=', $yesterday)
            ->groupBy('domain_id')
            ->orderBy('total_usd','desc')
            ->limit(5)->get();

        return $this->success([
            'price' => [
                'total' => $totalUsd->total,
                'web' => $mostMoney,
            ],
            'numbers'=>[
                'quantity'=>$orders->count(),
                'web'=>$mostNumbers,
            ]
        ]);
    }

}
