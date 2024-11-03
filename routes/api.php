<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\RoamingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TroubleshootingController;

Route::post('/block-wrong-transaction', [TransactionController::class, 'blockWrongTransaction'])->name('block.wrong.transaction');

Route::post('/activate-roaming', [RoamingController::class, 'activateRoaming'])->name('activate.roaming');

Route::post('/sim-details', [SimDetailsController::class, 'submitForm'])->name('sim.details.submit');

Route::post('/send-advertisement-sms', [SmsController::class, 'sendAdvertisementSms'])->name('send.advertisement.sms');

Route::post('/new-fiber-installation', [TransactionController::class, 'newFiberInstallation'])->name('new.fiber.installation');
                    
Route::post('/request-troubleshooting', [TroubleshootingController::class, 'requestTroubleshooting'])->name('request.troubleshooting');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// Define your API routes here
Route::post('whatsapp/test', [WhatsAppBotController::class, 'testMessage']);
Route::post('whatsapp/incoming', [WhatsAppBotController::class, 'handleIncomingMessage']);