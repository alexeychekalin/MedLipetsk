<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patients;

class SSEController extends Controller
{
    /**
     * SSE поток для данных пациентов (автоподключение)
     */
    public function patientStream()
    {
        config(['session.driver' => 'array']);

        return response()->stream(function () {
            set_time_limit(0);
            ignore_user_abort(true);

            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            ob_implicit_flush(true);

            // Отправляем начальное сообщение
            echo "event: connected\n";
            echo "data: " . json_encode([
                    'message' => 'Подключение к пациентам установлено',
                    'time' => now()->toDateTimeString()
                ]) . "\n\n";
            flush();

            $lastMessageTime = time();
            $counter = 0;

            while (true) {
                if (connection_aborted() === 1) {
                    break;
                }

                // Проверяем новые сообщения
                $newMessages = $this->checkForNewMessages($lastMessageTime);

                foreach ($newMessages as $message) {
                    if (isset($message['event'])) {
                        echo "event: {$message['event']}\n";
                        echo "data: " . json_encode($message['data'] ?? $message) . "\n\n";
                    } else {
                        echo "data: " . json_encode($message) . "\n\n";
                    }
                    flush();
                    $lastMessageTime = time();
                }

                // Ping каждые 30 секунд
                if ($counter % 30 === 0) {
                    echo ": ping\n\n";
                    flush();
                }

                sleep(2);
                $counter++;

                if ($counter > 1800) break; // 30 минут
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Отправка полных данных пациента
     */
    public function sendFullPatient(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|string|exists:patients,id'
        ]);

        $patient = Patients::find($validated['patient_id']);

        if (!$patient) {
            return response()->json([
                'status' => 'error',
                'message' => 'Пациент не найден'
            ], 404);
        }

        // Получаем ВСЕ данные пациента
        $patientData = [
            'id' => $patient->id,
            'second_name' => $patient->second_name,
            'first_name' => $patient->first_name,
            'patronymic_name' => $patient->patronymic_name,
            'phone_number' => $patient->phone_number,
            'balance' => $patient->balance,
            'passport' => $patient->passport,
            'info' => $patient->info,
            'image' => $patient->image,
            'created_at' => $patient->created_at,
            'updated_at' => $patient->updated_at,
            // Добавляем вычисляемые поля
            'full_name' => trim($patient->second_name . ' ' . $patient->first_name . ' ' . ($patient->patronymic_name ?? '')),
            'formatted_phone' => $this->formatPhone($patient->phone_number),
            'formatted_balance' => number_format($patient->balance, 2, '.', ' ') . ' ₽'
        ];

        $message = [
            'id' => uniqid(),
            'event' => 'patient_full_data',
            'data' => $patientData,
            'timestamp' => time(),
            'time' => now()->toDateTimeString()
        ];

        $this->saveMessageToFile($message);

        Log::info('Full patient data sent via SSE', $patientData);

        return response()->json([
            'status' => 'success',
            'message' => 'Полные данные пациента отправлены',
            'patient' => $patientData
        ]);
    }

    /**
     * Автоматический поиск и отправка полных данных по номеру
     */
    public function sendFullPatientByPhone(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string'
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone_number']);

        $patient = Patients::where('phone_number', 'like', '%' . $cleanPhone . '%')
            ->orWhere('phone_number', 'like', '%' . substr($cleanPhone, -10) . '%')
            ->first();

        if ($patient) {
            // Полные данные пациента
            $patientData = [
                'id' => $patient->id,
                'second_name' => $patient->second_name,
                'first_name' => $patient->first_name,
                'patronymic_name' => $patient->patronymic_name,
                'phone_number' => $patient->phone_number,
                'balance' => $patient->balance,
                'passport' => $patient->passport,
                'info' => $patient->info,
                'image' => $patient->image,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'full_name' => trim($patient->second_name . ' ' . $patient->first_name . ' ' . ($patient->patronymic_name ?? '')),
                'formatted_phone' => $this->formatPhone($patient->phone_number),
                'formatted_balance' => number_format($patient->balance, 2, '.', ' ') . ' ₽'
            ];

            $message = [
                'id' => uniqid(),
                'event' => 'patient_full_data',
                'data' => $patientData,
                'timestamp' => time(),
                'time' => now()->toDateTimeString()
            ];
        } else {
            $message = [
                'id' => uniqid(),
                'event' => 'patient_not_found',
                'data' => [
                    'searched_phone' => $cleanPhone,
                    'message' => 'Пациент не найден',
                    'timestamp' => now()->toDateTimeString()
                ],
                'timestamp' => time()
            ];
        }

        $this->saveMessageToFile($message);

        return response()->json([
            'status' => 'success',
            'message' => 'Поиск пациента выполнен',
            'patient_found' => $patient !== null,
            'patient' => $patient ? $patientData : null
        ]);
    }

    /**
     * Форматирование номера телефона
     */
    private function formatPhone($phone)
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (preg_match('/^(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})$/', $clean, $matches)) {
            return "+{$matches[1]} ({$matches[2]}) {$matches[3]}-{$matches[4]}-{$matches[5]}";
        }
        return $phone;
    }

    /**
     * Проверяет новые сообщения в файле
     */
    private function checkForNewMessages($lastTime)
    {
        $messages = [];
        $filePath = storage_path('app/sse_messages.json');

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $allMessages = json_decode($content, true) ?? [];

            foreach ($allMessages as $message) {
                if ($message['timestamp'] > $lastTime) {
                    $messages[] = $message;
                }
            }
        }

        return $messages;
    }

    /**
     * Сохраняет сообщение в файл
     */
    private function saveMessageToFile($message)
    {
        $filePath = storage_path('app/sse_messages.json');
        $messages = [];

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $messages = json_decode($content, true) ?? [];
        }

        $messages[] = $message;

        if (count($messages) > 50) {
            $messages = array_slice($messages, -50);
        }

        file_put_contents($filePath, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // ... остальные методы ...
}
