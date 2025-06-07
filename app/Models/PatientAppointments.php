<?php

namespace App\Models;

use App\Http\Resources\PatientsResource;
use App\Http\Resources\PatientSummaryResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PatientAppointments extends Model
{
    protected $table = 'patient_appointments';

    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'uuid';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'scheduled_time',
        'duration',
        'patient_id',
        'status',
        'registration_date',
        'registrar',
        'receipt_id',
        'schedule_id',
        'patient_comment',
        'sms_notification_sent',
    ];

    public function patient()
    {
        return $this->belongsTo(Patients::class);
    }
    public function note()
    {
        return $this->hasOne(Notes::class, 'patient_appointment');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipts::class, 'receipt_id');
    }
}
