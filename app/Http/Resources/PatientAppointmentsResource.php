<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientAppointmentsResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'scheduled_time' => $this->scheduled_time,
            'duration' => $this->duration,
            'patient_id' => $this->patient_id,
            'status' => $this->status,
            'registration_date' => $this->registration_date,
            'registrar' => $this->registrar,
            'receipt_id' => $this->receipt_id,
            'schedule_id' => $this->schedule_id,
            'patient_comment' => $this->patient_comment,
            'sms_notification_sent' => $this->sms_notification_sent,
        ];

        if (str_contains($request->path(), 'v1')) {
            $data['patient'] = $this->patient ? new PatientSummaryResource($this->patient) : null;
            $data['note'] = $this->note ? new NoteSummaryResource($this->note) : null;
        }

        return $data;
    }
}
