<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Patients extends Model
{
    protected $table = 'patients';
    public $timestamps = false;
    use HasUuids;
    protected $guarded = [];

    protected $fillable = [
        'second_name',
        'first_name',
        'patronymic_name',
        'phone_number',
        'balance',
        'passport',
        'info',
        'created_at',
        'image'
    ];

    public function getCreatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }
        // преобразуем строку в Carbon, устанавливаем таймзону и форматируем
        return \Carbon\Carbon::parse($value)
            ->setTimezone('Europe/Moscow')
            ->toIso8601String();
    }

    public function updateBalance($increment = 0)
    {
        $this->balance += $increment;
        $this->save();
        return $this;
    }

    /*
    // Геттеры и мутаторы для зашифрованных полей
    public function getSecondNameAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
    public function setSecondNameAttribute($value)
    {
        $this->attributes['second_name'] = $value !== null ? Crypt::encryptString($value) : null;
    }

    public function getFirstNameAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value !== null ? Crypt::encryptString($value) : null;
    }

    public function getPatronymicNameAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
    public function setPatronymicNameAttribute($value)
    {
        $this->attributes['patronymic_name'] = $value !== null ? Crypt::encryptString($value) : null;
    }

    public function getPhoneNumberAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = $value !== null ? Crypt::encryptString($value) : null;
    }

    public function getImageAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $value !== null ? Crypt::encryptString($value) : null;
    }
    */
}
