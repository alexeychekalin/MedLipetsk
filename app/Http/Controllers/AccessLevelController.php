<?php

namespace App\Http\Controllers;

use App\Models\AccessLevel;
use Illuminate\Http\Request;
use App\Http\Resources\AccessLevelResource;

class AccessLevelController extends Controller
{
    public function index()
    {
        return AccessLevelResource::collection(AccessLevel::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:access_level,name',
        ]);
        $level = AccessLevel::create($validated);
        return new AccessLevelResource($level);
    }

    public function show(AccessLevel $accessLevel)
    {
        return new AccessLevelResource($accessLevel);
    }

    public function update(Request $request, AccessLevel $accessLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:access_level,name,' . $accessLevel->id,
        ]);
        $accessLevel->update($validated);
        return new AccessLevelResource($accessLevel);
    }

    public function destroy(AccessLevel $accessLevel)
    {
        $accessLevel->delete();
        return response()->json(['message' => 'Уровень доступа удален']);
    }
}
