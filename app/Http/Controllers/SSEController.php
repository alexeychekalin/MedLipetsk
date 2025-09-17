<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SSEController extends Controller
{
    /**
     * SSE поток без блокировки сессии
     */
    public function simpleStream()
    {
        // Отключаем сессию для этого endpoint
        config(['session.driver' => 'array']);

        return response()->stream(function () {
            set_time_limit(0);
            ignore_user_abort(true);

            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            ob_implicit_flush(true);

            // Отправляем начальное сообщение
            echo "data: " . json_encode([
                    'type' => 'connected',
                    'message' => 'Подключение установлено',
                    'time' => now()->toDateTimeString()
                ]) . "\n\n";
            flush();

            $lastMessageTime = time();
            $counter = 0;

            while (true) {
                if (connection_aborted() === 1) {
                    break;
                }

                // Проверяем новые сообщения в файле
                $newMessages = $this->checkForNewMessages($lastMessageTime);

                foreach ($newMessages as $message) {
                    echo "data: " . json_encode($message) . "\n\n";
                    flush();
                    $lastMessageTime = time();
                }

                // Отправляем ping каждые 15 секунд
                if ($counter % 15 === 0) {
                    echo ": ping\n\n";
                    flush();
                }

                sleep(1);
                $counter++;

                if ($counter > 3600) break;
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Endpoint для отправки уведомлений (работает параллельно)
     */
    public function sendEvent(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'type' => 'sometimes|string|in:info,success,warning,error'
        ]);

        // Сохраняем сообщение в файл
        $message = [
            'id' => uniqid(),
            'type' => $validated['type'] ?? 'info',
            'message' => $validated['message'],
            'timestamp' => time(),
            'time' => now()->toDateTimeString()
        ];

        $this->saveMessageToFile($message);

        Log::info('SSE Event saved to file', $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Событие сохранено и будет доставлено',
            'event_id' => $message['id']
        ]);
    }
    public function sendEvent2($message)
    {

        $this->saveMessageToFile($message);

        Log::info('SSE Event saved to file', $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Событие сохранено и будет доставлено',
            'event_id' => $message['id']
        ]);
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

        // Сохраняем только последние 50 сообщений
        if (count($messages) > 50) {
            $messages = array_slice($messages, -50);
        }

        file_put_contents($filePath, json_encode($messages, JSON_PRETTY_PRINT));
    }

    /**
     * Очистка сообщений
     */
    public function clearMessages()
    {
        $filePath = storage_path('app/sse_messages.json');
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Сообщения очищены'
        ]);
    }

    public function status()
    {
        return response()->json([
            'status' => 'active',
            'time' => now()->toDateTimeString(),
            'message' => 'SSE сервер работает без блокировки сессии'
        ]);
    }
}
