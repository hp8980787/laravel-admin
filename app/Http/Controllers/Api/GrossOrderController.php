<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Resources\GrossOrderCollnection;
use App\Models\GrossOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrossOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->keyword) {
            $orders = GrossOrder::query()
                ->with('items')
                ->orderBy('created_at', 'desc')
                ->orWhere('trans_id', 'like', "%$request->keyword%")
                ->orWhere('table_name', 'like', "%$request->keyword%")
                ->orWhere('info', 'like', "%$request->keyword%")
                ->paginate(20);
        } else {
            $orders = GrossOrder::query()->orderBy('created_at', 'desc')
                ->paginate(20);
        }
        return $this->success(new GrossOrderCollnection($orders));

    }

    public function update(Request $request, $id)
    {
        $data = $request->only('pids', 'order_status,', 'record_status');
        GrossOrder::query()->where('id', $id)->update($data);
        return $this->message('修改成功!');
    }

    public function record(Request $request)
    {
        $data = $request->data;
        $ids = array_column($data, 'id');
        GrossOrder::query()->whereIn('id',$ids)->update([
            'record_status'=>1
        ]);
        $orders = GrossOrder::query()->with('items')->whereIn('id',$ids)->get();
        foreach ($orders as $order){
            foreach ($order->items as $item) {
                $res = DB::table('product as p')->where('product_id', $item->product_id)->join('storehouse_product as s', 'p.id', '=', 's.product_id')
                    ->get();

                if (sizeof($res) <= 0) {
                    $order->order_status = Order::ORDER_STATUS_SOLD_OUT;
                    $order->save();
                    continue;
                }

                foreach ($res as $val) {
                    if (intval($val->amount) - intval($item->amount) < 0) {
                        $order->order_status = Order::ORDER_STATUS_SOLD_OUT;
                        $order->save();
                        continue;
                    } elseif (intval($val->amount) - intval($item->amount) >= 0) {
                        $order->order_status = Order::ORDER_STATUS_IN_STOCK;
                        $order->save();
                    }

                }


            }
        }
        return $this->success($ids);
    }
}
