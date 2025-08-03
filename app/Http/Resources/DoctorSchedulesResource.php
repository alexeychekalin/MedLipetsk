<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorSchedulesResource extends JsonResource
{
    public function toArray($request)
    {
        $data =  [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'cabinet' => $this->cabinet,
            'starting' => $this->starting,
            'ending' => $this->ending,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];

        if (str_contains($request->path(), 'v1')) {
            $data['note'] = $this->note ? new NoteSummaryResource($this->note) : null;
            $data['patient_appointments'] = PatientAppointmentsResource::collection(
                $this->whenLoaded('patientAppointments'));
        }

        return $data;
    }
}
