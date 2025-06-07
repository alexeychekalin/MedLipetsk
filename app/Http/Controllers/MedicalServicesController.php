<?php

namespace App\Http\Controllers;

use App\Models\MedicalServices;
use Illuminate\Http\Request;
use App\Http\Resources\MedicalServicesResource;

class MedicalServicesController extends Controller
{
    public function index()
    {
        return MedicalServicesResource::collection(MedicalServices::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pricelist_item_id' => 'required|exists:pricelist_items,id',
            'treatment_plan_price' => 'nullable|numeric',
            'quantity' => 'required|integer|min:1',
            'performer_id' => 'nullable|exists:doctors,id',
            'agent_id' => 'nullable|exists:doctors,id',
            'conclusion' => 'nullable|string', // или 'nullable|json' при хранении байтовых данных
            'receipt_id' => 'nullable|exists:receipts,id',
            'date' => 'nullable|date',
        ]);
        $service = MedicalServices::create($validated);
        return new MedicalServicesResource($service);
    }

    public function show(MedicalServices $medicalService)
    {
        return new MedicalServicesResource($medicalService);
    }

    public function update(Request $request, MedicalServices $medicalService)
    {
        $validated = $request->validate([
            'pricelist_item_id' => 'required|exists:pricelist_items,id',
            'treatment_plan_price' => 'nullable|numeric',
            'quantity' => 'required|integer|min:1',
            'performer_id' => 'nullable|exists:doctors,id',
            'agent_id' => 'nullable|exists:doctors,id',
            'conclusion' => 'nullable|string',
            'receipt_id' => 'nullable|exists:receipts,id',
            'date' => 'nullable|date',
        ]);
        $medicalService->update($validated);
        return new MedicalServicesResource($medicalService);
    }

    public function destroy(MedicalServices $medicalService)
    {
        $medicalService->delete();
        return response()->json(['message' => 'Услуга удалена']);
    }
}
