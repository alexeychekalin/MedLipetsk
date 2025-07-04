<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MedicalServices extends Model
{
    protected $table = 'medical_services';

    public $incrementing = false;
    protected $keyType = 'uuid';
    public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'pricelist_item_id',
        'treatment_plan_price',
        'quantity',
        'performer_id',
        'agent_id',
        'conclusion',
        'receipt_id',
        'date',
        'created_at',
    ];

    public function pricelistItem()
    {
        return $this->belongsTo(\App\Models\PricelistItem::class, 'pricelist_item_id');
    }

    public function performer()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'performer_id');
    }

    public function agent()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'agent_id');
    }

    public function receipt()
    {
        return $this->belongsTo(\App\Models\Receipts::class, 'receipt_id');
    }

    // Геттер для поля conclusion (расшифровка при чтении)
    public function getConclusionAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Мутатор для поля conclusion (зашифровка при сохранении)
    public function setConclusionAttribute($value)
    {
        $this->attributes['conclusion'] = Crypt::encryptString($value);
    }

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
    public function getDateAttribute($value)
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
