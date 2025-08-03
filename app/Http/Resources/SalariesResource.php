<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalariesResource extends JsonResource
{

    public function toArray($request)
    {
        //dd($this);
        return [
            'id' => $this->id,
            'type' => $this->type,
            'rate' => $this->rate,
            'monthly_amount' => $this->monthly_amount,
            'hourly_amount' => $this->hourly_amount,
            'doctor' => $this->doctor,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
