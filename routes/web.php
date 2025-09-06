<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SSEController;

// Основные SSE endpoints
Route::get('/sse/patient-stream', [SSEController::class, 'patientStream']);


// Тестовая страница
Route::get('/sse-test', function () {
    return view('sse-test');
});

Route::get('/', function () {
    return view('welcome');
});
