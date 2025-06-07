<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';
    use HasUuids;
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $casts = [
        'methods' => 'array'
    ];

    protected $fillable = [
        'id',
        'date',
        'purpose',
        'details',
        'methods',
        'receipt_id',
        'created_by',
        'doctor_id',
        'patient_id',
    ];

    public function doctor_id()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'doctors');
    }
    public function patient_id()
    {
        return $this->belongsTo(\App\Models\Patients::class, 'patients');
    }
}
