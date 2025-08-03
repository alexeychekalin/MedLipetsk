<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricelistItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomenklature' => $this->nomenklature,
            'category' => $this->category_rel ? $this->category_rel->name : null,
            'title' => $this->title,
            'price' => $this->price,
            'costprice' => $this->costprice,
            'archived' => $this->archived,
            'fixedsalary' => $this->fixedsalary,
            'fixedagentfee' => $this->fixedagentfee,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
