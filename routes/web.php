<?php
use App\Http\Controllers\SshController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;

Route::get('/sim-details', [SimDetailsController::class, 'showForm'])->name('sim.details.form');
Route::post('/sim-details', [SimDetailsController::class, 'submitForm'])->name('sim.details.submit');




Route::get('/', function () {
    return view('welcome');
});
