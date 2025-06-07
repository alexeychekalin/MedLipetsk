<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedules extends Model
{
    protected $table = 'doctor_schedules';

    public $incrementing = false;
    protected $keyType = 'uuid';
    public $timestamps = false;
    use HasUuids;
    protected $fillable = [
        'id',
        'doctor_id',
        'cabinet',
        'starting',
        'ending',
    ];

    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctors::class, 'doctor_id');
    }

    public function note()
    {
        return $this->hasOne(Notes::class, 'doctor_schedule');
    }

    public function patientappointments()
    {
        return $this->hasMany(PatientAppointments::class, 'schedule_id');
    }
}
