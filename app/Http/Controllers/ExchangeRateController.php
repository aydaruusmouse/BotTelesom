<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function getExchangeRate()
    {
        try {
            // Call the external API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://10.10.0.7:8077/api/KaaliyeApi/GetExchangeRate');

            // Parse the API response
            $responseData = $response->json();

            if ($response->successful()) {
                return view('exchange_rate', ['data' => $responseData['Data']]);
            }

            // Handle API errors
            return view('exchange_rate', ['error' => $responseData['Message'] ?? 'An error occurred.']);
        } catch (\Exception $e) {
            // Handle connection or other exceptions
            return view('exchange_rate', [
                'error' => 'Failed to fetch exchange rate: ' . $e->getMessage(),
            ]);
        }
    }
}
