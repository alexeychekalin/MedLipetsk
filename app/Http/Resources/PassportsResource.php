<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PassportsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'series_number' => $this->series_number,
            'authority' => $this->authority,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'issue_date' => $this->issue_date,
        ];
    }
}
