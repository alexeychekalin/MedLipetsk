<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SalariesSnapshot extends Model
{
    protected $table = 'salaries_snapshot';

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
        'date_start',
        'date_finish'
    ];

    public function doctor_rel()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'doctor');
    }
}
