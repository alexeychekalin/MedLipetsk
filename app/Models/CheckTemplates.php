<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CheckTemplates extends Model
{
    protected $table = 'check_templates';

    public $incrementing = false;
    protected $keyType = 'uuid';
    use HasUuids;
   // public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'discount',
        'medical_service',
    ];

    public function medicalService()
    {
        return $this->belongsTo(\App\Models\MedicalServices::class, 'medical_service');
    }
}
