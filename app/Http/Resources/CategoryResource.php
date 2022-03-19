<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'=>$this->id,
            'category_en' => $this->category_en,
            'category_fr' => $this->category_fr,
            'category_jp' => $this->category_jp,
            'category_de' => $this->category_de,
            'category_nl' => $this->category_nl,
            'category_pl' => $this->category_pl,
            'category_pt' => $this->category_pt,
            'category_es' => $this->category_es,
        ];
    }
}
