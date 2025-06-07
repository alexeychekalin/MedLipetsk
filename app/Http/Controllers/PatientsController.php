<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use Illuminate\Http\Request;
use App\Http\Resources\PatientsResource;

class PatientsController extends Controller
{
    public function index()
    {
        return PatientsResource::collection(Patients::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'second_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic_name' => 'nullable|string',
            'phone_number' => 'required|string',
            'balance' => 'numeric',
            'passport' => 'nullable|exists:passports,id',
            'info' => 'nullable|string',
            'image' => 'nullable|string', // или другой формат, по необходимости
        ]);
        $patient = Patients::create($validated);
        return new PatientsResource($patient);
    }

    public function show(Patients $patient)
    {
        return new PatientsResource($patient);
    }

    public function update(Request $request, Patients $patient)
    {
        $validated = $request->validate([
            'second_name' => 'required|string',
            'first_name' => 'required|string',
            'patronymic_name' => 'nullable|string',
            'phone_number' => 'required|string',
            'balance' => 'numeric',
            'passport' => 'nullable|exists:passports,id',
            'info' => 'nullable|string',
            'image' => 'nullable|string',
        ]);
        $patient->update($validated);
        return new PatientsResource($patient);
    }

    public function destroy(Patients $patient)
    {
        $patient->delete();
        return response()->json(['message' => 'Пациент удален']);
    }
}
