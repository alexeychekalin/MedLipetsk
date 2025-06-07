<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'purpose' => $this->purpose,
            'details' => $this->details,
            'methods' => $this->methods,
            'receipt_id' => $this->receipt_id,
            'created_by' => $this->created_by,
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
        ];
    }
}
