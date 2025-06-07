<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlans extends Model
{
    protected $table = 'treatment_plans';

    public $incrementing = false;
    protected $keyType = 'uuid';
    public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'patient',
        'kind',
        'starting_date',
        'expiration_date',
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patients::class, 'patient');
    }
}
