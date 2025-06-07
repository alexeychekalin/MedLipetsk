<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Salaries extends Model
{
    protected $table = 'salaries';

    public $incrementing = false;
    protected $keyType = 'uuid';
    public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'type',
        'rate',
        'monthly_amount',
        'hourly_amount',
        'doctor',
    ];

    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'doctor');
    }
}
