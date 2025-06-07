<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedules;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorSchedulesResource;

class DoctorSchedulesController extends Controller
{
    public function index()
    {
        return DoctorSchedulesResource::collection(DoctorSchedules::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'cabinet' => 'required|integer',
            'starting' => 'required|date_format:Y-m-d H:i:s',
            'ending' => 'required|date_format:Y-m-d H:i:s|after:starting',
        ]);
        $schedule = DoctorSchedules::create($validated);
        return new DoctorSchedulesResource($schedule);
    }

    public function show(DoctorSchedules $doctorSchedule)
    {
        return new DoctorSchedulesResource($doctorSchedule);
    }

    public function update(Request $request, DoctorSchedules $doctorSchedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'cabinet' => 'required|integer',
            'starting' => 'required|date_format:Y-m-d H:i:s',
            'ending' => 'required|date_format:Y-m-d H:i:s|after:starting',
        ]);
        $doctorSchedule->update($validated);
        return new DoctorSchedulesResource($doctorSchedule);
    }

    public function destroy(DoctorSchedules $doctorSchedule)
    {
        $doctorSchedule->delete();
        return response()->json(['message' => 'Расписание удалено']);
    }

    public function newShedule(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctors,id',
            'cabinet' => 'required|integer',
            'starting' => 'required|date_format:Y-m-d H:i:s',
            'ending' => 'required|date_format:Y-m-d H:i:s|after:starting',
        ]);

        // Создаём расписание
        $schedule = DoctorSchedules::create($validated);

        // Загружаем связи для ответа
        $schedule = DoctorSchedules::with([
            'note',
            'patientappointments.patient',
            'patientappointments.note'
        ])->findOrFail($schedule->id);

        return new DoctorSchedulesResource($schedule);
    }
}
