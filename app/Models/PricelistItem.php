<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PricelistItem extends Model
{
    protected $table = 'pricelist_items';

    protected $keyType = 'uuid';
    public $incrementing = false;
    public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'nomenklature',
        'category',
        'title',
        'price',
        'costprice',
        'archived',
        'fixedsalary',
        'fixedagentfee',
    ];

    // Связь с категорией (отделением)
    public function category_rel()
    {
        return $this->belongsTo(\App\Models\Department::class, 'category');
    }
}
