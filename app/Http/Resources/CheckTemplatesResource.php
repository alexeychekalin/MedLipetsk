<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckTemplatesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'discount' => $this->discount,
            'medical_service' => $this->medical_service,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
