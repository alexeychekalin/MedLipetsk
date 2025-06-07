<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PatientSummary extends Model
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
    ];

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

}
