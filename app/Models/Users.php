<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Users extends Model
{
    protected $table = 'users';

    public $timestamps = false;

    use HasUuids;

    protected $fillable = [
        'login',
        'password',
        'second_name',
        'first_name',
        'patronymic_name',
        'post',
    ];

    public $incrementing = false; // так как UUID
    protected $keyType = 'string';

    // Геттеры и мутаторы для зашифровки/расшифровки

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function setSecondNameAttribute($value)
    {
        if ($value !== null)
            $this->attributes['second_name'] = Crypt::encryptString($value);
        else
            $this->attributes['second_name'] = null;
    }

    public function getSecondNameAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }

    public function setFirstNameAttribute($value)
    {
        if ($value !== null)
            $this->attributes['first_name'] = Crypt::encryptString($value);
        else
            $this->attributes['first_name'] = null;
    }

    public function getFirstNameAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }

    public function setPatronymicNameAttribute($value)
    {
        if ($value !== null)
            $this->attributes['patronymic_name'] = Crypt::encryptString($value);
        else
            $this->attributes['patronymic_name'] = null;
    }

    public function getPatronymicNameAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
}
