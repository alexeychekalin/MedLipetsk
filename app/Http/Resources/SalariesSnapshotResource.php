<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalariesSnapshotResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'rate' => $this->rate,
            'monthly_amount' => $this->monthly_amount,
            'hourly_amount' => $this->hourly_amount,
            'doctor' => $this->doctor,
            'date_start' => $this->date_start,
            'date_finish' => $this->date_finish,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
