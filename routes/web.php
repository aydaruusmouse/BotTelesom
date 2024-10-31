<?php
use App\Http\Controllers\SshController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;
use App\Http\Controllers\SmsController;


Route::get('/sim-details', [SimDetailsController::class, 'showForm'])->name('sim.details.form');

Route::get('/sms-form', [SmsController::class, 'showForm'])->name('sms.form');
Route::post('/send-advertisement-sms', [SmsController::class, 'sendAdvertisementSms'])->name('send.advertisement.sms');


Route::get('/', function () {
    return view('welcome');
});
