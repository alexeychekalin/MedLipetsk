<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricelistItemsTreatmentPlans extends Model
{
    protected $table = 'pricelist_items_treatment_plans';

    //public $timestamps = false;
    protected $primaryKey = 'id_pricelist_item';
    protected $keyType = 'string';
    protected $fillable = [
        'id_pricelist_item',
        'id_treatment_plan',
    ];

    public function pricelistItem()
    {
        return $this->belongsTo(\App\Models\PricelistItem::class, 'id_pricelist_item');
    }

    public function treatmentPlan()
    {
        return $this->belongsTo(\App\Models\TreatmentPlans::class, 'id_treatment_plan');
    }
}
