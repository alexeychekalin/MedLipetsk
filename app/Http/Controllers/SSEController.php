<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patients;

class SSEController extends Controller
{
    /**
     * SSE поток для клиентов
     */
    public function stream()
    {
        config(['session.driver' => 'array']);

        return response()->stream(function () {
            set_time_limit(0);
            ignore_user_abort(true);

            // Очищаем буферы
            while (ob_get_level() > 0) ob_end_clean();
            ob_implicit_flush(true);

            // Отправляем начальное сообщение
            echo "event: connected\n";
            echo "data: " . json_encode(['message' => 'SSE Connected']) . "\n\n";
            flush();

            $filePath = storage_path('app/sse_messages.json');
            $lastCheck = time();

            while (true) {
                if (connection_aborted()) break;

                // Проверяем новые сообщения каждую секунду
                if (file_exists($filePath)) {
                    $messages = json_decode(file_get_contents($filePath), true) ?? [];

                    foreach ($messages as $message) {
                        if (isset($message['event']) && isset($message['data'])) {
                            echo "event: {$message['event']}\n";
                            echo "data: " . json_encode($message['data']) . "\n\n";
                        } else {
                            echo "data: " . json_encode($message) . "\n\n";
                        }
                        flush();
                    }

                    // Очищаем файл после отправки
                    file_put_contents($filePath, json_encode([]));
                }

                sleep(1); // Проверяем каждую секунду
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * Endpoint для отправки данных пациента
     */
    public function sendPatient(Request $request)
    {
        $phone = $request->input('phone_number');
        if (!$phone) {
            return response()->json(['error' => 'Phone number required'], 400);
        }

        // Очищаем номер
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        // Ищем пациента
        $patient = Patients::where('phone_number', 'like', '%' . $cleanPhone . '%')->first();

        // Формируем сообщение
        $message = [
            'event' => 'patient_data',
            'data' => $patient ? [
                'id' => $patient->id,
                'second_name' => $patient->second_name,
                'first_name' => $patient->first_name,
                'patronymic_name' => $patient->patronymic_name,
                'phone_number' => $patient->phone_number,
                'balance' => $patient->balance,
                'passport' => $patient->passport,
                'info' => $patient->info,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'full_name' => trim($patient->second_name . ' ' . $patient->first_name . ' ' . ($patient->patronymic_name ?? ''))
            ] : [
                'message' => 'Patient not found',
                'searched_phone' => $cleanPhone
            ]
        ];

        // Сохраняем в файл
        $this->saveMessage($message);

        return response()->json([
            'status' => 'success',
            'message' => 'Patient data sent to SSE',
            'patient_found' => !!$patient
        ]);
    }

    /**
     * Простое сохранение сообщения в файл
     */
    private function saveMessage($message)
    {
        $filePath = storage_path('app/sse_messages.json');
        $messages = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) ?? [] : [];
        $messages[] = $message;
        file_put_contents($filePath, json_encode($messages));
    }
}
