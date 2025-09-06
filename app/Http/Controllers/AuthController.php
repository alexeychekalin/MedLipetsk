<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientSummaryResource;
use App\Models\Patients;
use App\Models\PatientSummary;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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

    public function call_events(Request $request)
    {
        $fromNumber = $request['from_number'];
        preg_match('/sip:(\d+)@/', $fromNumber, $matches);
        $phoneNumber = $matches[1] ?? '';
        $phoneNumber = substr($fromNumber, 4, strpos($fromNumber, '@') - 4);
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        $patient = Patients::where('phone_number', 'like', '%' . $cleanPhone . '%')
            ->orWhere('phone_number', 'like', '%' . substr($cleanPhone, -10) .'%')
            ->first();

        // Отправляем полные данные пациента через SSE
        if ($patient) {
            Http::post('http://83.166.244.225/api/sse/send-full-patient', [
                'patient_id' => $patient->id
            ]);
        } else {
            Http::post('http://83.166.244.225/api/sse/find-full-patient', [
                'phone_number' => $cleanPhone
            ]);
        }

        return new PatientSummaryResource($patient);
    }
}
