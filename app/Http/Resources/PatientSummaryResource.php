<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'second_name' => $this->second_name,
            'first_name' => $this->first_name,
            'patronymic_name' => $this->patronymic_name,
            'phone_number' => $this->phone_number,
        ];
    }
}
