<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'pid'=>$this->pid,
            'local'=>$this->local,
            'sku'=>$this->pcode,
            'img'=>$this->okpcode,
            'brand'=>$this->cs,
            'category_id'=>$this->category_id,
            'type'=>$this->type,
            'size'=>$this->size,
            'color'=>$this->color,
            'dl'=>$this->dl,
            'dy'=>$this->dy,
            'bzq'=>$this->bzq,
            'comp'=>$this->comp,
            'rep'=>$this->rep,
            'jianjie1'=>$this->jianjie1,
            'jianjie2'=>$this->jianjie2

        ];
    }
}
