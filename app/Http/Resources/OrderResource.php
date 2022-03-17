<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'domain'=>DomainResource::make($this->domain),
            'trans_id'=>$this->trans_id,
            'user_id'=>$this->user_id,
            'info'=> json_encode(array_reverse(json_decode($this->info,true))),
            'total'=>$this->total,
            'total_usd'=>$this->total_usd,
            'currency'=>$this->currency,
            'order_status'=>$this->order_status,
            'buy_date'=>$this->buy_time,
            'created_at'=>$this->created_at,
            'ip'=>$this->ip,

        ];
    }
}
