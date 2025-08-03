<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public static $wrap = null;
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'startingcash' => $this->startingcash,
            'created_at' => $this->created_at,
            //'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ];
    }
}
