<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PricelistItemSnapshot extends Model
{
    protected $table = 'pricelist_items_snapshot';

    protected $keyType = 'uuid';
    public $incrementing = false;
    public $timestamps = false;
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
}
