<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Resources\GrossOrderCollnection;
use App\Models\GrossOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class GrossOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->keyword) {
            $orders = GrossOrder::query()->orderBy('created_at', 'desc')
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
        $data =  $request->data;
        foreach ($data as $val){
            Order::query()->create($val);
        }

        return $this->success($request->data);
    }
}
