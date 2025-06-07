<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;
use App\Http\Resources\NotesResource;

class NotesController extends Controller
{
    public function index()
    {
        return NotesResource::collection(Notes::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:120',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'doctor_schedule' => 'nullable|exists:doctor_schedules,id',
            'patient_appointment' => 'nullable|exists:patient_appointments,id',
        ]);
        $note = Notes::create($validated);
        return new NotesResource($note);
    }

    public function show(Notes $note)
    {
        return new NotesResource($note);
    }

    public function update(Request $request, Notes $note)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:120',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'doctor_schedule' => 'nullable|exists:doctor_schedules,id',
            'patient_appointment' => 'nullable|exists:patient_appointments,id',
        ]);
        $note->update($validated);
        return new NotesResource($note);
    }

    public function destroy(Notes $note)
    {
        $note->delete();
        return response()->json(['message' => 'Запись удалена']);
    }
}
