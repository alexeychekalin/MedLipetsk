<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlans;
use Illuminate\Http\Request;
use App\Http\Resources\TreatmentPlansResource;

class TreatmentPlansController extends Controller
{
    public function index()
    {
        return TreatmentPlansResource::collection(TreatmentPlans::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient' => 'nullable|exists:patients,id',
            'kind' => 'required|string',
            'starting_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:starting_date',
        ]);
        $treatmentPlan = TreatmentPlans::create($validated);
        return new TreatmentPlansResource($treatmentPlan);
    }

    public function show(TreatmentPlans $treatmentPlan)
    {
        return new TreatmentPlansResource($treatmentPlan);
    }

    public function update(Request $request, TreatmentPlans $treatmentPlan)
    {
        $validated = $request->validate([
            'patient' => 'nullable|exists:patients,id',
            'kind' => 'required|string',
            'starting_date' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:starting_date',
        ]);
        $treatmentPlan->update($validated);
        return new TreatmentPlansResource($treatmentPlan);
    }

    public function destroy(TreatmentPlans $treatmentPlan)
    {
        $treatmentPlan->delete();
        return response()->json(['message' => 'План лечения удален']);
    }
}
