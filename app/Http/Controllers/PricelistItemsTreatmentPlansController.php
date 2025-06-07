<?php

namespace App\Http\Controllers;

use App\Models\PricelistItemsTreatmentPlans;
use Illuminate\Http\Request;
use App\Http\Resources\PricelistItemsTreatmentPlansResource;

class PricelistItemsTreatmentPlansController extends Controller
{
    public function index()
    {
        return PricelistItemsTreatmentPlansResource::collection(PricelistItemsTreatmentPlans::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pricelist_item' => 'required|exists:pricelist_items,id',
            'id_treatment_plan' => 'required|exists:treatment_plans,id',
        ]);
        $relation = PricelistItemsTreatmentPlans::create($validated);
        return new PricelistItemsTreatmentPlansResource($relation);
    }

    public function show($id)
    {
        return  PricelistItemsTreatmentPlansResource::collection(PricelistItemsTreatmentPlans::where('id_pricelist_item', $id)->get());
    }

    public function update(Request $request, PricelistItemsTreatmentPlans $relation)
    {
        $validated = $request->validate([
            'id_pricelist_item' => 'required|exists:pricelist_items,id',
            'id_treatment_plan' => 'required|exists:treatment_plans,id',
        ]);
        $relation->update($validated);
        return new PricelistItemsTreatmentPlansResource($relation);
    }

    public function destroy(PricelistItemsTreatmentPlans $relation)
    {
        $relation->delete();
        return response()->json(['message' => 'Связь удалена']);
    }
}
