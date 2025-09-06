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
        // Извлекаем номер из запроса
        $fromNumber = $request['from_number'];
        preg_match('/sip:(\d+)@/', $fromNumber, $matches);
        $phoneNumber = $matches[1] ?? substr($fromNumber, 4, strpos($fromNumber, '@') - 4);
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Ищем пациента
        $patient = Patients::where('phone_number', 'like', '%' . $cleanPhone . '%')->first();

        // Отправляем данные через SSE (простой HTTP запрос)
        $this->sendToSSE($cleanPhone);

        return new PatientSummaryResource($patient);
    }

    /**
     * Отправка данных в SSE
     */
    private function sendToSSE($phoneNumber)
    {
        try {
            // Простой HTTP запрос без зависимостей
            $url = 'https://83.166.244.225/sse/send-patient';
            $data = http_build_query(['phone_number' => $phoneNumber]);

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => $data,
                    'timeout' => 2 // Таймаут 2 секунды
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            @file_get_contents($url, false, $context);

        } catch (\Exception $e) {
            // Просто логируем ошибку, не прерываем работу
            Log::info('SSE send background: ' . $e->getMessage());
        }
    }
}
