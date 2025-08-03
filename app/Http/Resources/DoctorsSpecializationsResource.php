<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorsSpecializationsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id_doctor' => $this->id_doctor,
            'id_specialization' => $this->id_specialization,
            'is_basic' => $this->is_basic,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
