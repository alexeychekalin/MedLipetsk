<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersAccessLevel extends Model
{
    protected $table = 'users_access_level';
    //public $timestamps = false;
    protected $primaryKey = 'id_user';
    protected $keyType = 'string';
    protected $guarded = [];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(Users::class, 'id_user');
    }

    // Связь с уровнем доступа
    public function accessLevel()
    {
        return $this->belongsTo(AccessLevel::class, 'id_access_level');
    }

    // В случае, нужны ли кастомные даты или поля, можно их добавить
}
