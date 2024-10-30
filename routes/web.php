<?php
use App\Http\Controllers\SshController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\SimDetailsController;

Route::get('/sim-details', [SimDetailsController::class, 'showForm'])->name('sim.details.form');
Route::post('/sim-details', [SimDetailsController::class, 'submitForm'])->name('sim.details.submit');

Route::get('/whatsapp/test', function () {
    return view('whatsapp_test');
});
// Change the route if you prefer API routes
// Route::post('/whatsapp/incoming', [WhatsAppBotController::class, 'handleIncomingMessage']);
Route::get('/whatsapp/test', [WhatsAppBotController::class, 'testMessage']);


Route::post('/whatsapp/test', [WhatsAppBotController::class, 'testMessage']);
Route::get('/test-api', function () {
    $response = \Illuminate\Support\Facades\Http::get('172.16.53.200'); // Adjust URL as necessary

    return $response->json(); // Return the JSON response
});

Route::get('/', function () {
    return view('welcome');
});
