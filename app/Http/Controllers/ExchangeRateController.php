<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeRateController extends Controller
{
    public function getExchangeRate(Request $request)
    {
        try {
            // Define the headers and the data payload
            $headers = [
                'apiTokenUser' => 'mob#!Billing!*',
                'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
                'Content-Type' => 'application/json',
            ];
            
            $data = [
                'Search' => 'DSL Installation',
            ];

            // Make the API request with headers and data
            $response = Http::withHeaders($headers)->post('http://10.10.0.7:8077/api/KaaliyeApi/GetExchangeRate', $data);

            // Parse the API response
            $responseData = $response->json();

            if ($response->successful()) {
                // Return data to the view
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
