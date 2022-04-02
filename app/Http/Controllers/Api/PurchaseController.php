<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as Controller;
use App\Http\Requests\Api\PurchaseRequest;
use App\Http\Resources\PurchaseCollection;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $data = Purchase::query()->with(['product', 'storehouse'])->paginate(20);
        return $this->success(new PurchaseCollection($data));
    }

    public function store(PurchaseRequest $request)
    {
        $data = $request->only('product_id', 'storehouse_id', 'amount', 'price', 'total', 'remark', 'item_id');
        Purchase::query()->create($data);
        return $this->message('添加成功!');
    }

    public function update(PurchaseRequest $request, $id)
    {
        $data = $request->only('product_id', 'storehouse_id', 'amount', 'price', 'total', 'remark', 'item_id');
        Purchase::query()->where('id', $id)->update($data);
        return $this->message('修改成功!');
    }

    public function completed($id)
    {
        $purchase = Purchase::query()->findOrFail($id);
        if ($purchase->status === Purchase::PURCHASE_STATUS_COMPLETED) {
            return $this->failed('该订单已完成!');
        }
        //采购完成时，给仓库添加库存
        try {
            DB::beginTransaction();
            Purchase::query()->where('id', $id)->update(['status' => Purchase::PURCHASE_STATUS_COMPLETED]);
            $stock = DB::table('storehouse_product')->where('storehouse_id', $purchase->storehouse_id)
                ->where('product_id', $purchase->product_id)->first();
            if ($stock) {
                DB::table('storehouse_product')->lockForUpdate()->where('id', $stock->id)->update([
                    'amount' => $purchase->amount + $stock->amount
                ]);
            } else {
                DB::table('storehouse_product')->lockForUpdate()->insert([
                    'product_id' => $purchase->product_id,
                    'storehouse_id' => $purchase->storehouse_id,
                    'amount' => $purchase->amount,
                    'created_at' => now('Asia/Shanghai'),
                    'updated_at' => now('Asia/Shanghai')
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

        return $this->message('完成!');
    }

    public function purchase($id)
    {
        $purchase = Purchase::query()->where('product_id',$id)->get();
    }
}
