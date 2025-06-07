<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';

    public $incrementing = false;
    protected $keyType = 'uuid';
    use HasUuids;
    protected $fillable = [
        'id',
        'text',
        'created_by',
        'updated_by',
        'doctor_schedule',
        'patient_appointment',
    ];

    public function doctorSchedule()
    {
        return $this->belongsTo(\App\Models\DoctorSchedules::class, 'doctor_schedule');
    }

    public function patientAppointment()
    {
        return $this->belongsTo(\App\Models\PatientAppointments::class, 'patient_appointment');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Users::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\Users::class, 'updated_by');
    }
}
