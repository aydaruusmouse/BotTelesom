<?php
use App\Http\Controllers\SshController;
use App\Http\Controllers\ExchangeRateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\RoamingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TroubleshootingController;
use App\Http\Controllers\USSDController;

Route::get('/ussd/menu', [USSDController::class, 'showMenuForm'])->name('ussd.menu'); // Display the form

Route::get('/block-transaction-form', [TransactionController::class, 'showBlockTransactionForm'])->name('block.transaction.form');
Route::get('/fiber-installation-form', [TransactionController::class, 'showFiberInstallationForm'])->name('fiber.installation.form');

Route::get('/troubleshooting-form', [TroubleshootingController::class, 'showTroubleshootingForm'])->name('troubleshooting.form');

// roamin route
Route::get('/activate-roaming-form', [RoamingController::class, 'showActivateRoamingForm'])->name('activate.roaming.form');
// pinkpuk route
Route::get('/sim-details', [SimDetailsController::class, 'showForm'])->name('sim.details.form');
// sms advertisement route
Route::get('/sms-form', [SmsController::class, 'showForm'])->name('sms.form');
// Route::post('/send-advertisement-sms', [SmsController::class, 'sendAdvertisementSms'])->name('send.advertisement.sms');
Route::post('/whatsapp/test', [WhatsAppBotController::class, 'testMessage'])->name('whatsapp.test');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/exchange-rate', [ExchangeRateController::class, 'getExchangeRate'])->name('exchange.rate');

// For testing with Blade template
Route::get('/subscription', function () {
    return view('subscription');
})->name('subscription');
