<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PricelistItemSnapshot extends Model
{
    protected $table = 'pricelist_items_snapshot';

    protected $keyType = 'uuid';
    public $incrementing = false;
    //public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'nomenklature',
        'id_pricelist_item',
        'price',
        'costprice',
        'fixedsalary',
        'fixedsgentfee',
        'date_start',
        'date_finish',
    ];

    public function pricelistItem_rel()
    {
        return $this->belongsTo(\App\Models\PricelistItem::class, 'id_pricelist_item');
    }
    public function getDateStartAttribute($value)
    {
        if (!$value) {
            return null;
        }
        // преобразуем строку в Carbon, устанавливаем таймзону и форматируем
        return \Carbon\Carbon::parse($value)
            ->setTimezone('Europe/Moscow')
            ->toIso8601String();
    }
    public function getDateFinishAttribute($value)
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
