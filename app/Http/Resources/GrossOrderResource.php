<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GrossOrderResource extends JsonResource
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
            'domain_id'=>$this->domain_id,
            'domain'=>DomainResource::make($this->domain),
            'table_name'=>$this->table_name,
            'trans_id'=>$this->trans_id,
            'user_id'=>$this->user_id,
            'info'=> json_encode(array_reverse(json_decode($this->info,true))),
            'pids'=>$this->pids,
            'total'=>$this->total,
            'total_usd'=>$this->total_usd,
            'currency'=>$this->currency,
            'record_status'=>$this->record_status,
            'order_status'=>$this->order_status,
            'buy_date'=>$this->buy_time,
            'created_at'=>$this->created_at,
            'ip'=>$this->ip,
        ];
    }
}
