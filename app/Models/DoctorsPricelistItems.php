<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorsPricelistItems extends Model
{
    protected $table = 'doctors_pricelist_items';

    //public $timestamps = false;
    protected $primaryKey = 'id_doctor';
    protected $keyType = 'string';
    protected $fillable = [
        'id_doctor',
        'id_pricelist_item',
        'is_basic',
    ];

    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'id_doctor');
    }

    public function pricelistItem()
    {
        return $this->belongsTo(\App\Models\PriceListItem::class, 'id_pricelist_item');
    }
}
