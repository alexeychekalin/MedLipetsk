<?php

namespace App\Http\Controllers;

use App\Models\DoctorsSpecializations;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorsSpecializationsResource;

class DoctorsSpecializationsController extends Controller
{
    public function index()
    {
        return DoctorsSpecializationsResource::collection(DoctorsSpecializations::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_doctor' => 'required|exists:doctors,id',
            'id_specialization' => 'required|exists:dict_specializations,id',
            'is_basic' => 'boolean',
        ]);
        $relation = DoctorsSpecializations::create($validated);
        return new DoctorsSpecializationsResource($relation);
    }

    public function show($id)
    {
        return  DoctorsSpecializationsResource::collection(DoctorsSpecializations::where('id_doctor', $id)->get());
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_doctor' => 'required|exists:doctors,id',
            'id_specialization' => 'required|exists:dict_specializations,id',
            'is_basic' => 'boolean',
        ]);
        $item = DoctorsSpecializations::where('id_doctor', $id)->first();
        $item->update($validated);
        $item->refresh();  // обязательно, если нужно убедиться, что модель актуальна
        return new DoctorsSpecializationsResource($item);
    }

    public function destroy(DoctorsSpecializations $relation)
    {
        $relation->delete();
        return response()->json(['message' => 'Связь удалена']);
    }
}
