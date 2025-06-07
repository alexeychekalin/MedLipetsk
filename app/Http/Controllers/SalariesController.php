<?php

namespace App\Http\Controllers;

use App\Models\Salaries;
use Illuminate\Http\Request;
use App\Http\Resources\SalariesResource;

class SalariesController extends Controller
{
    public function index()
    {
        $salaries = Salaries::all();
        return SalariesResource::collection($salaries);
       // return SalariesResource::collection(Salaries::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Сдельная,Ежемесячная,Почасовая',
            'rate' => 'nullable|numeric',
            'monthly_amount' => 'nullable|integer',
            'hourly_amount' => 'nullable|integer',
            'doctor' => 'nullable|exists:doctors,id',
        ]);
        $salary = Salaries::create($validated);
        return new SalariesResource($salary);
    }

    public function show(Salaries $salary)
    {
        return new SalariesResource($salary);
    }

    public function update(Request $request, Salaries $salary)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Сдельная,Ежемесячная,Почасовая',
            'rate' => 'nullable|numeric',
            'monthly_amount' => 'nullable|integer',
            'hourly_amount' => 'nullable|integer',
            'doctor' => 'nullable|exists:doctors,id',
        ]);
        $salary->update($validated);
        return new SalariesResource($salary);
    }

    public function destroy(Salaries $salary)
    {
        $salary->delete();
        return response()->json(['message' => 'Зарплата удалена']);
    }
}
