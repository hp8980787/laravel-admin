<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'name' => $this->name,
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'status_text' => Order::ORDER_STATUS_GROUP[$this->status],
            'status_type' => Order::ORDER_STATUS_TAG_GROUP[$this->status],
        ];
    }
}
