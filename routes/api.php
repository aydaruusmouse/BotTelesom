<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;
use App\Http\Controllers\SmsController;



Route::post('/sim-details', [SimDetailsController::class, 'submitForm'])->name('sim.details.submit');

Route::post('/send-advertisement-sms', [SmsController::class, 'sendAdvertisementSms'])->name('send.advertisement.sms');



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Define your API routes here
Route::post('whatsapp/test', [WhatsAppBotController::class, 'testMessage']);
Route::post('whatsapp/incoming', [WhatsAppBotController::class, 'handleIncomingMessage']);