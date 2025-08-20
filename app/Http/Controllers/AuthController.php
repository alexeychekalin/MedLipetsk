<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users_laravel|max:20',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'phone' => $attr['phone']
        ]);

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=> $token
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response('Login invalid', 503);
        }

        return response()->json([
            'token'=> explode("|", $user->createToken($request->device_name)->plainTextToken)[1]
        ]);
    }

    public function allusers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function call_events()
    {
        //$users = User::all();
        return response()->json('token-ok');
    }
}
