<?php

namespace App\Http\Controllers;

use App\Models\CheckTemplates;
use Illuminate\Http\Request;
use App\Http\Resources\CheckTemplatesResource;

class CheckTemplatesController extends Controller
{
    public function index()
    {
        return CheckTemplatesResource::collection(CheckTemplates::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'discount' => 'nullable|numeric',
            'medical_service' => 'nullable|exists:medical_services,id',
        ]);
        $template = CheckTemplates::create($validated);
        return new CheckTemplatesResource($template);
    }

    public function show(CheckTemplates $checkTemplate)
    {
        return new CheckTemplatesResource($checkTemplate);
    }

    public function update(Request $request, CheckTemplates $checkTemplate)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'discount' => 'nullable|numeric',
            'medical_service' => 'nullable|exists:medical_services,id',
        ]);
        $checkTemplate->update($validated);
        return new CheckTemplatesResource($checkTemplate);
    }

    public function destroy(CheckTemplates $checkTemplate)
    {
        $checkTemplate->delete();
        return response()->json(['message' => 'Шаблон чека удален']);
    }
}
