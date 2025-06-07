<?php

namespace App\Http\Controllers;

use App\Models\PatientAppointments;
use Illuminate\Http\Request;
use App\Http\Resources\PatientAppointmentsResource;

class PatientAppointmentsController extends Controller
{
    public function index()
    {
        return PatientAppointmentsResource::collection(PatientAppointments::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scheduled_time' => 'required|date_format:Y-m-d H:i:s',
            'duration' => 'required|int',
            'patient_id' => 'nullable|exists:patients,id',
            'status' => 'required|string',
            'registration_date' => 'nullable|date_format:Y-m-d H:i:s',
            'registrar' => 'nullable|exists:users,id',
            'receipt_id' => 'nullable|exists:receipts,id',
            'schedule_id' => 'nullable|exists:doctor_schedules,id',
            'patient_comment' => 'nullable|string',
            'sms_notification_sent' => 'boolean',
        ]);
        $appointment = PatientAppointments::create($validated);
        return new PatientAppointmentsResource($appointment);
    }

    public function show(PatientAppointments $patientAppointment)
    {
        return new PatientAppointmentsResource($patientAppointment);
    }

    public function update(Request $request, PatientAppointments $patientAppointment)
    {
        $validated = $request->validate([
            'scheduled_time' => 'required|date_format:Y-m-d H:i:s',
            'duration' => 'required|string',
            'patient_id' => 'nullable|exists:patients,id',
            'status' => 'required|string',
            'registration_date' => 'nullable|date_format:Y-m-d H:i:s',
            'registrar' => 'nullable|exists:users,id',
            'receipt_id' => 'nullable|exists:receipts,id',
            'schedule_id' => 'nullable|exists:doctor_schedules,id',
            'patient_comment' => 'nullable|string',
            'sms_notification_sent' => 'boolean',
        ]);
        $patientAppointment->update($validated);
        return new PatientAppointmentsResource($patientAppointment);
    }

    public function destroy(PatientAppointments $patientAppointment)
    {
        $patientAppointment->delete();
        return response()->json(['message' => 'Запись удалена']);
    }
}
