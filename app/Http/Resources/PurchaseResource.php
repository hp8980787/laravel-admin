<?php

namespace App\Http\Resources;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'storehouse_id' => $this->storehouse_id,
            'amount' => $this->amount,
            'price' => $this->price,
            'total' => $this->total,
            'remark' => $this->remark,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'status_text' => Purchase::PURCHASE_STATUS_GROUP[$this->status],
            'status_type' => Purchase::PURCHASE_STATUS_TAG_GROUP[$this->status],
            'product' => [
                'id' => $this->product->id,
                'img' => $this->product->okpcode,
                'sku' => $this->product->pcode,
                'brand' => $this->product->cs,
                'slug' => $this->product->jianjie1,
            ],
            'storehouse' => $this->storehouse
        ];
    }
}
