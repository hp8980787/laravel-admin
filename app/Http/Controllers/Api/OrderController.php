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
    public $weekGroup = [
        0 => '星期日',
        1 => '星期一',
        2 => '星期二',
        3 => '星期三',
        4 => '星期四',
        5 => '星期五',
        6 => '星期六',
    ];
    public $monthGroup = [
        '01' => '一月',
        '02' => '二月',
        '03' => '三月',
        '04' => '四月',
        '05' => '五月',
        '06' => '六月',
        '07' => '七月',
        '08' => '八月',
        '09' => '九月',
        '10' => '十月',
        '11' => '十一月',
        '12' => '十二月'
    ];

    protected function transDate(string $type, $key): string
    {
        switch ($type){
            case 'day':
                return $key;
            case 'week':
              return   $this->weekGroup[$key];
            case  'month':
                return $this->monthGroup[$key];
        }
    }

    public function index()
    {
        $orders = Order::query()->orderBy('created_at','desc')->paginate(16);
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
            $data['week'][] = $this->weekGroup[$order['week']];
            $data['orders'][] = $order['count'];
        }
        return $this->success($data);
    }

    public function statistic(Request $request)
    {
        $now = Carbon::now();
        $type = $request->type;
        switch ($type) {
            case 'day':
                $min = Carbon::now()->subMonth();
                $sql = "DATE_FORMAT(`created_at`,'%d')as $type,count(domain_id) count,max(created_at)as created_at,sum(total_usd)as total";
                break;
            case 'week':
                $min = Carbon::now()->subDays(6);
                $sql = "DATE_FORMAT(`created_at`,'%w')as $type,count(domain_id) count,max(created_at)as created_at,sum(total_usd)as total";
                break;
            case  'month':
                $sql = "DATE_FORMAT(`created_at`,'%m')as $type,count(domain_id) count,max(created_at)as created_at,sum(total_usd)as total";
                $year = Carbon::now()->year;
                $min = Carbon::create($year);
                break;
        }
        $orders = Order::query()->selectRaw($sql)
            ->groupBy($request->type)
            ->minTime($min)
            ->orderBy('created_at', 'asc')
            ->get()->toArray();
        $data = [];

        foreach ($orders as $order) {
            $data[$type][] = $this->transDate($type, $order[$type]);
            $data['orders'][] = $order['count'];
            $data['total'][] = $order['total'];
        }

        return $this->success($data);
    }

}
