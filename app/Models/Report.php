<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'startingcash',
        'date_cash',
        'created_by',
    ];

    public function payments()
    {
        return $this->hasMany(Payments::class);
    }

    public function getDateCashAttribute($value)
    {
        if (!$value) {
            return null;
        }
        // преобразуем строку в Carbon, устанавливаем таймзону и форматируем
        return \Carbon\Carbon::parse($value)
            ->setTimezone('Europe/Moscow')
            ->toIso8601String();
    }

    public function getCreatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }
        return \Carbon\Carbon::parse($value)
            ->setTimezone('Europe/Moscow')
            ->toIso8601String();
    }
}
