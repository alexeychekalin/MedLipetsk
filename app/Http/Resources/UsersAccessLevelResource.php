<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersAccessLevelResource extends JsonResource
{
    public function toArray($request)
    {
        //dd($this->user->id);
        return [
            //'user' => new \App\Http\Resources\UsersResource($this->user->id),
            //'access_level' => new \App\Http\Resources\AccessLevelResource($this->accessLevel->id),
            'user' => $this->user->id,
            'access_level' => $this->accessLevel->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
