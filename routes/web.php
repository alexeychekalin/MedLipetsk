<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSEController;

// Основные SSE endpoints
Route::get('/sse/simple', [SSEController::class, 'simpleStream'])->name('sse.simple');
Route::post('/sse/send', [SSEController::class, 'sendEvent'])->name('sse.send');
Route::get('/sse/status', [SSEController::class, 'status'])->name('sse.status');
Route::get('/sse/clear', [SSEController::class, 'clearMessages'])->name('sse.clear');

// Тестовая страница
Route::get('/sse-test', function () {
    return view('sse-test');
});

Route::get('/', function () {
    return view('welcome');
});
