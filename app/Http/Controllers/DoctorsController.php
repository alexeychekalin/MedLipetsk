<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorsResource;
use App\Models\Doctors;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    public function index()
    {
        return DoctorsResource::collection(Doctors::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'second_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic_name' => 'nullable|string',
            'phone_number' => 'required|string',
            'birth_date' => 'required|date',
            'department' => 'required|exists:dict_departments,id',
            'service_duration' => 'nullable|integer', // можно уточнить
            'default_cabinet' => 'required|integer',
            'balance' => 'numeric',
            'info' => 'nullable|string',
            'image' => 'nullable|string',
            'id_user' => 'nullable|exists:users,id',
            'vacation_schedule' => 'nullable|json',
            'as_patient' => 'nullable|exists:patients,id',
            'rating' => 'numeric|min:0|max:5',
        ]);
        $doctor = Doctors::create($validated);
        return new DoctorsResource($doctor);
    }

    public function show(Doctors $doctor)
    {
        return new DoctorsResource($doctor);
    }

    public function update(Request $request, Doctors $doctor)
    {
        $validated = $request->validate([
            'second_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic_name' => 'nullable|string',
            'phone_number' => 'required|string',
            'birth_date' => 'required|date',
            'degree' => 'nullable|exists:dict_departments,id',
            'service_duration' => 'required|integer',
            'default_cabinet' => 'required|integer',
            'balance' => 'numeric',
            'info' => 'nullable|string',
            'image' => 'nullable|string',
            'id_user' => 'nullable|exists:users,id',
            'vacation_schedule' => 'nullable|json',
            'as_patient' => 'nullable|exists:patients,id',
            'rating' => 'numeric|min:0|max:5',
        ]);
        $doctor->update($validated);
        return new DoctorsResource($doctor);
    }

    public function destroy(Doctors $doctor)
    {
        $doctor->delete();
        return response()->json(['message' => 'Доктор удален']);
    }
}
