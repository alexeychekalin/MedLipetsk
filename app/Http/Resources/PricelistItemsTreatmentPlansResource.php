<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricelistItemsTreatmentPlansResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_pricelist_item' => $this->id_pricelist_item,
            'id_treatment_plan' => $this->id_treatment_plan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
