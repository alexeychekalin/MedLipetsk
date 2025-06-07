<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'startingcash' => $this->startingcash,
            'date_cash' => $this->date_cash,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ];
    }
}
