<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ShipRequest;
use App\Http\Resources\OrderResource;
use App\Models\GrossOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Ship;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as Controller;
use Illuminate\Support\Facades\DB;

class ShipController extends Controller
{
    public function ship(Request $request)
    {
        $id = $request->id;
        $order = GrossOrder::query()->with('items')->findOrFail($id);
//        $products = Product::query()->with('stocks')->whereIn('id', $ids)->get();
        foreach ($order->items as $item) {
            $res = DB::table('product as p')->where('product_id', $item->product_id)->join('storehouse_product as s', 'p.id', '=', 's.product_id')
                ->get();

            if (sizeof($res) <= 0) {
                $order->order_status = Order::ORDER_STATUS_SOLD_OUT;
                $order->save();
                return $this->failed('需要采购');

            }

            foreach ($res as $val) {
                if (intval($val->amount) - intval($item->amount) < 0) {
                    $order->order_status = Order::ORDER_STATUS_SOLD_OUT;
                    $order->save();
                    return $this->failed('需要采购');
                } elseif (intval($val->amount) - intval($item->amount) >= 0) {
                    $order->order_status = Order::ORDER_STATUS_IN_STOCK;
                    $order->save();
                }

            }


        }

        return $this->success(OrderResource::make($order));
    }

    public function store(ShipRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->only('order_id', 'no', 'price', 'completed_at', 'express_company');
            Ship::query()->create($data);
            $order = Order::query()->where('id', $request->order_id)->first();
            $order->order_status = Order::ORDER_STATUS_SHIPPED;
            $order->save();
            DB::commit();;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->failed($exception->getMessage());
        }


        return $this->message('添加成功!');
    }
}
