<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function showForm()
    {
        return view('sms_response');
    }

    public function sendAdvertisementSms(Request $request)
{
    $request->validate([
        'msisdn' => 'required|string',
        'status' => 'required|string',
    ]);

    // Extract the msisdn from the request
    $msisdn = $request->input('msisdn');

    // Clean the msisdn by removing "whatsapp:" prefix and any extra spaces
    $msisdn = str_replace('whatsapp:', '', $msisdn); // Remove "whatsapp:" prefix
    $msisdn = str_replace('+', '', $msisdn); // Remove any '+' signs
    $msisdn = preg_replace('/^\D/', '', $msisdn); // Remove any non-digit characters from the beginning
    $msisdn = preg_replace('/[^0-9]/', '', $msisdn); // Remove any non-digit characters
    $msisdn = trim($msisdn); // Trim any whitespace

    // Log the cleaned msisdn for debugging purposes
    \Log::info('Processed MSISDN:', ['msisdn' => $msisdn]);

    $status = $request->input('status');

    try {
        // Pass only the cleaned msisdn to the API
        $response = Http::withHeaders([
            'apiTokenUser' => 'mob#!Billing!*',
            'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
            'Content-Type' => 'application/json',
        ])->timeout(20)
        ->post('http://10.10.0.7:8077/api/KaaliyeApi/AdvertisementSMS', [
            'msisdn' => $msisdn, // Only the cleaned number
            'status' => $status,
        ]);

        // Return JSON response and display cleaned msisdn for confirmation
        return response()->json([
            'status' => $response->json('status'),
            'message' => $response->json('Message'),
            'data' => $response->json('Data'),
            'processed_msisdn' => $msisdn, // Display trimmed msisdn
        ]);
    } catch (\Exception $e) {
        \Log::error('Error sending SMS: ' . $e->getMessage());

        return response()->json([
            'error' => 'An error occurred while sending the SMS.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


}