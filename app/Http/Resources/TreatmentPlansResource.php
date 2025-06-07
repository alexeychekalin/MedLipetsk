<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentPlansResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'patient' => $this->patient,
            'kind' => $this->kind,
            'starting_date' => $this->starting_date,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
