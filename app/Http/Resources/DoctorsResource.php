<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'second_name' => $this->second_name,
            'first_name' => $this->first_name,
            'patronymic_name' => $this->patronymic_name,
            'phone_number' => $this->phone_number,
            'birth_date' => $this->birth_date,
            'department' => $this->department,
            'service_duration' => $this->service_duration,
            'default_cabinet' => $this->default_cabinet,
            'balance' => $this->balance,
            'info' => $this->info,
            //'created_at' => $this->created_at,
            'image' => $this->image,
            'id_user' => $this->id_user,
            'vacation_schedule' => $this->vacation_schedule,
            'as_patient' => $this->as_patient,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
