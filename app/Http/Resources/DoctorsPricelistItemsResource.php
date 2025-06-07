<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorsPricelistItemsResource extends JsonResource
{
    public function toArray($request)
    {
        //dd($this);
        return [
            'id_doctor' => $this->id_doctor,
            'id_pricelist_item' => $this->id_pricelist_item,
            'is_basic' => $this->is_basic,
        ];
    }
}
