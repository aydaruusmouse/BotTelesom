<?php
use App\Http\Controllers\SshController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\RoamingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TroubleshootingController;

Route::get('/block-transaction-form', [TransactionController::class, 'showBlockTransactionForm'])->name('block.transaction.form');
Route::get('/fiber-installation-form', [TransactionController::class, 'showFiberInstallationForm'])->name('fiber.installation.form');

Route::get('/troubleshooting-form', [TroubleshootingController::class, 'showTroubleshootingForm'])->name('troubleshooting.form');

// roamin route
Route::get('/activate-roaming-form', [RoamingController::class, 'showActivateRoamingForm'])->name('activate.roaming.form');
// pinkpuk route
Route::get('/sim-details', [SimDetailsController::class, 'showForm'])->name('sim.details.form');
// sms advertisement route
Route::get('/sms-form', [SmsController::class, 'showForm'])->name('sms.form');
Route::post('/send-advertisement-sms', [SmsController::class, 'sendAdvertisementSms'])->name('send.advertisement.sms');


Route::get('/', function () {
    return view('welcome');
});
