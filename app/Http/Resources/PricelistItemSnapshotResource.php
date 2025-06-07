<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricelistItemSnapshotResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nomenklature' => $this->nomenklature,
            'id_pricelist_item' => $this->pricelistItem_rel ? $this->pricelistItem_rel->title : null,
            'price' => $this->price,
            'costprice' => $this->costprice,
            'fixedsalary' => $this->fixedsalary,
            'fixedagentfee' => $this->fixedagentfee,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
        ];
    }
}
