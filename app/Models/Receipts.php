<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Receipts extends Model
{
    protected $table = 'receipts';

    public $incrementing = false;
    //public $timestamps = false;
    use HasUuids;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'total_amount',
        'discount',
        'created_at',
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
}
