<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Define your API routes here
// Route::post('whatsapp/test', [WhatsAppBotController::class, 'testMessage']);

Route::get('/whatsapp/incoming', [WhatsAppBotController::class, 'verifyWebhook']);
Route::post('/whatsapp/incoming', [WhatsAppBotController::class, 'handleIncomingMessage']);

// // pinkpuk route
// Route::post('/get-sim-details', [WhatsAppBotController::class, 'getSimDetails']);

// Route::get('/sim-form', [WhatsAppBotController::class, 'showForm'])->name('sim-form');