<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;
use App\Http\Resources\SpecializationResource;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::all();
        return SpecializationResource::collection($specializations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);
        $specialization = Specialization::create($validated);
        return new SpecializationResource($specialization);
    }

    public function show(Specialization $specialization)
    {
        return new SpecializationResource($specialization);
    }

    public function update(Request $request, Specialization $specialization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);
        $specialization->update($validated);
        return new SpecializationResource($specialization);
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return response()->json(['message' => 'Специализация удалена']);
    }
}
