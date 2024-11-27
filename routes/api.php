<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\RoamingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TroubleshootingController;
use App\Http\Controllers\ChatwootController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\USSDController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\CustomerSupportController;


Route::middleware('prevent-clickjacking')->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/auth/login', function () {
        return view('auth.login');
    })->name('admin.login');
});


Route::post('/support/get-references', [CustomerSupportController::class, 'getReferences'])->name('support.getReferences');


Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('otp.send');

// Route for initiating the USSD session (first request with empty data)
Route::post('/ussd/initiatesession', [UssdController::class, 'initiateUssdSession'])->name('ussd.initiatesession');

// Route for continuing the USSD session with user data (second request with user input)
Route::post('/ussd/continuesession', [UssdController::class, 'continueUssdSession'])->name('ussd.continuesession');

// Route::post('/ussd/process', [USSDController::class, 'processUSSD'])->name('ussd.process'); // Process the form submission

Route::post('/check-subscription', [SubscriptionController::class, 'checkSubscription'])->name('check.subscription');
Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::post('/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

Route::post('/send-message', [ChatwootController::class, 'sendMessage']);
Route::post('/webhook/incoming-message', [ChatwootController::class, 'receiveIncomingMessage']);

// Route::post('/webhook/chatwoot/incoming-message', [ChatwootController::class, 'handleIncomingMessage']);

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


// Route::post('whatsapp/incoming', [WhatsAppBotController::class, 'handleIncomingMessage']);



// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/activate-roaming', [RoamingController::class, 'activateRoaming']);
// });
