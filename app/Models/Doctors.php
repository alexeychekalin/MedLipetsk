<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Doctors extends Model
{
    protected $table = 'doctors';

    public $incrementing = false;
    protected $keyType = 'uuid';
    public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'second_name',
        'first_name',
        'patronymic_name',
        'phone_number',
        'birth_date',
        'department',
        'service_duration',
        'default_cabinet',
        'balance',
        'info',
        'id_user',
        'vacation_schedule',
        'as_patient',
        'rating',
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

    /*
    // Шифрование полей
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

    public function getInfoAttribute($value)
    {
        return $value !== null ? Crypt::decryptString($value) : null;
    }
    public function setInfoAttribute($value)
    {
        $this->attributes['info'] = $value !== null ? Crypt::encryptString($value) : null;
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
    // Связи
    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'department');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\Users::class, 'id_user');
    }
    public function asPatient()
    {
        return $this->belongsTo(\App\Models\Patients::class, 'as_patient');
    }
}
