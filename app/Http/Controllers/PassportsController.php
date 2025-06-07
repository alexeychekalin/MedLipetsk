<?php

namespace App\Http\Controllers;

use App\Models\Passports;
use Illuminate\Http\Request;
use App\Http\Resources\PassportsResource;

class PassportsController extends Controller
{
    public function index()
    {
        $passports = Passports::all();
        return PassportsResource::collection($passports);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'series_number' => 'required|string',
            'authority' => 'required|string',
            'gender' => 'required|in:M,F',
            'birthday' => 'required|date',
            'issue_date' => 'required|date',
        ]);
        $passport = Passports::create($validated);
        return new PassportsResource($passport);
    }

    public function show(Passports $passport)
    {
        return new PassportsResource($passport);
    }

    public function update(Request $request, Passports $passport)
    {
        $validated = $request->validate([
            'series_number' => 'required|string',
            'authority' => 'required|string',
            'gender' => 'required|in:M,F',
            'birthday' => 'required|date',
            'issue_date' => 'required|date',
        ]);
        $passport->update($validated);
        return new PassportsResource($passport);
    }

    public function destroy(Passports $passport)
    {
        $passport->delete();
        return response()->json(['message' => 'Паспорт удален']);
    }
}
