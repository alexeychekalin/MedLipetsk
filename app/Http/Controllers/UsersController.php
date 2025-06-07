<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return UsersResource::collection(Users::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string',
            'second_name' => 'nullable|string',
            'first_name' => 'nullable|string',
            'patronymic_name' => 'nullable|string',
            'post' => 'nullable|string',
        ]);
        $user = Users::create($validated);
        return new UsersResource($user);
    }

    public function show(Users $user)
    {
        return new UsersResource($user);
    }

    public function update(Request $request, Users $user)
    {
        $validated = $request->validate([
            'login' => 'required|string|unique:users,login,' . $user->id,
            'password' => 'required|string',
            'second_name' => 'nullable|string',
            'first_name' => 'nullable|string',
            'patronymic_name' => 'nullable|string',
            'post' => 'nullable|string',
        ]);
        $user->update($validated);
        return new UsersResource($user);
    }

    public function destroy(Users $user)
    {
        $user->delete();
        return response()->json(['message' => 'Пользователь удален']);
    }
}
