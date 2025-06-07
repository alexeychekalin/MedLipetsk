<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Passports extends Model
{
    protected $table = 'passports';
    public $timestamps = false;
    use HasUuids;
    protected $keyType = 'uuid';
    // Открытые для массового заполнения поля
    protected $fillable = [
        'series_number',
        'authority',
        'gender',
        'birthday',
        'issue_date',
    ];


    // Геттер для поля series_number (расшифровка при чтении)
    public function getSeriesNumberAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Мутатор для поля series_number (зашифровка при сохранении)
    public function setSeriesNumberAttribute($value)
    {
        $this->attributes['series_number'] = Crypt::encryptString($value);
    }

    // Геттер для поля authority (расшифровка при чтении)
    public function getAuthorityAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Мутатор для поля authority (зашифровка при сохранении)
    public function setAuthorityAttribute($value)
    {
        $this->attributes['authority'] = Crypt::encryptString($value);
    }
}
