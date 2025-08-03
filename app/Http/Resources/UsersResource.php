<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            // Пароль — обычно не показываем
            // 'password' => $this->password,
            'second_name' => $this->second_name,
            'first_name' => $this->first_name,
            'patronymic_name' => $this->patronymic_name,
            'post' => $this->post,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
