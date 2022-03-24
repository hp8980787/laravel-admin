<?php

namespace App\Http\Controllers\Api;

use App\Models\GrossOrder;
use App\Models\Product;
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
            if (sizeof($res)<=0) {
                return $this->failed("需要采购", 400);
            }

            foreach ($res as $val) {
                if (intval($val->amount) - intval($item->amount) < 0) {
                    return $this->failed("$val->pcode 数量不够,需要采购", 400);
                }
            }
            return $this->message('1');
        }


    }
}
