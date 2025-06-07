<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'second_name' => $this->second_name,
            'first_name' => $this->first_name,
            'patronymic_name' => $this->patronymic_name,
            'phone_number' => $this->phone_number,
            'balance' => $this->balance,
            'passport' => $this->passport, // здесь можно расширить, например, сериализовать паспорт
            'info' => $this->info,
            'created_at' => $this->created_at,
            'image' => $this->image,
        ];
    }
}
