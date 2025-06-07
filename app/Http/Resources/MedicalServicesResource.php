<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalServicesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pricelist_item_id' => $this->pricelist_item_id,
            'treatment_plan_price' => $this->treatment_plan_price,
            'quantity' => $this->quantity,
            'performer_id' => $this->performer_id,
            'agent_id' => $this->agent_id,
            'conclusion' => $this->conclusion,
            'receipt_id' => $this->receipt_id,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ];
    }
}
