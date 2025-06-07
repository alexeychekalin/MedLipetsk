<?php

namespace App\Http\Controllers;

use App\Models\UsersAccessLevel;
use Illuminate\Http\Request;
use App\Http\Resources\UsersAccessLevelResource;

class UsersAccessLevelController extends Controller
{
    public function index()
    {
        return UsersAccessLevelResource::collection(UsersAccessLevel::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_access_level' => 'required|exists:access_level,id',
        ]);
        $item = UsersAccessLevel::create($validated);
        return $item; //new UsersAccessLevelResource($relation);
    }

    public function show($id_user)
    {
        //$item = UsersAccessLevel::where('id_user', $id_user)->first();
        return  UsersAccessLevelResource::collection(UsersAccessLevel::where('id_user', $id_user)->get());
    }

    public function update(Request $request, $id_user)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_access_level' => 'required|exists:access_level,id',
        ]);
        $item = UsersAccessLevel::where('id_user', $id_user)->first();
        $item->update($validated);
        $item->refresh();  // обязательно, если нужно убедиться, что модель актуальна
        return new UsersAccessLevelResource($item);
    }

    public function destroy(UsersAccessLevel $relation)
    {
        $relation->delete();
        return response()->json(['message' => 'Связь удалена']);
    }
}
