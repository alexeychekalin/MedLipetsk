<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorsSpecializations extends Model
{
    protected $table = 'doctors_specializations';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'id_doctor';
    protected $keyType = 'string';
   // protected $primaryKey = ['id_doctor', 'id_specialization'];

    protected $fillable = [
        'id_doctor',
        'id_specialization',
        'is_basic',
    ];

    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'id_doctor');
    }

    public function specialization()
    {
        return $this->belongsTo(\App\Models\Specialization::class, 'id_specialization');
    }
}
