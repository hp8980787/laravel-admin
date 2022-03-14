<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Resources\OrdersCollection;
use App\Models\Country;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public $weeklyGroup = [
        0 => '星期天',
        1 => '星期一',
        2 => '星期二',
        3 => '星期三',
        4 => '星期四',
        5 => '星期五',
        6 => '星期六',
    ];

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
            ->orderBy('numbers', 'desc')
            ->limit(5)->get();
        $totalUsd = Order::query()->selectRaw('sum(total_usd)as total')
            ->where('created_at', '>=', $yesterday)->first();
        $mostMoney = Order::query()->with('domain')
            ->selectRaw('domain_id,sum(total_usd)as total_usd,max(created_at)as created_at')
            ->where('created_at', '>=', $yesterday)
            ->groupBy('domain_id')
            ->orderBy('total_usd', 'desc')
            ->limit(5)->get();

        return $this->success([
            'price' => [
                'total' => $totalUsd->total,
                'web' => $mostMoney,
            ],
            'numbers' => [
                'quantity' => $orders->count(),
                'web' => $mostNumbers,
            ]
        ]);
    }

    public function mothCountryStatistics(Request $request)
    {
        if (Cache::has('mothCountryStatistics')) {
            $countriesOrder = Cache::get('mothCountryStatistics');
        } else {
            $startOfMonth = Carbon::now()->startOfMonth();
            $table = app(Order::class)->getTable();
            $countriesOrder = DB::connection('order')->table("$table as o")
                ->selectRaw('count(d.country_id)as nums,d.country_id,min(`o`.`created_at`)as created_at,sum(o.total_usd)as total')
                ->leftJoin('domains as d', 'o.domain_id', '=', 'd.id')
                ->where('o.created_at', '>=', $startOfMonth)
                ->groupBy('d.country_id')
                ->orderBy('nums', 'desc')
                ->get();
            foreach ($countriesOrder as $item) {
                $item->country = Country::query()->where('id', $item->country_id)->first()->name;
            }
            Cache::put('mothCountryStatistics', $countriesOrder, 1036800);
        }


        return $this->success($countriesOrder);
    }

    public function weeklyOrders(Request $request)
    {
        $now = Carbon::now();
        $min = Carbon::now()->subDays(6);
        $orders = Order::query()->selectRaw("DATE_FORMAT(`created_at`,'%w')as week,count(domain_id) count,max(created_at)as created_at ")
            ->groupBy('week')
            ->where('created_at', '>=', $min)
            ->orderBy('created_at', 'asc')
            ->get()->toArray();
        $data = [];
        foreach ($orders as $order) {
            $data['week'][] = $this->weeklyGroup[$order['week']];
            $data['orders'][] = $order['count'];
        }
        return $this->success($data);
    }

}
