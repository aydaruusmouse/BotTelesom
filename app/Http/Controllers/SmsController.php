<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function showForm()
    {
        // Show the form view where users can input msisdn and status
        return view('sms_response'); // Ensure this view exists for form input
    }

    public function sendAdvertisementSms(Request $request)
    {
        $request->validate([
            'msisdn' => 'required|string',
            'status' => 'required|string',
        ]);

        $msisdn = $request->input('msisdn');
        $status = $request->input('status');

        try {
            $response = Http::withHeaders([
                'apiTokenUser' => 'mob#!Billing!*',
                'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
                'Content-Type' => 'application/json',
            ])->timeout(20) // Increase timeout to 20 seconds
            ->post('http://10.10.0.7:8077/api/KaaliyeApi/AdvertisementSMS', [
                'msisdn' => $msisdn,
                'status' => $status,
            ]);

            // Return JSON response
            return response()->json([
                'status' => $response->json('status'),
                'message' => $response->json('Message'),
                'data' => $response->json('Data'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending SMS: ' . $e->getMessage());

            // Return error response as JSON
            return response()->json([
                'error' => 'An error occurred while sending the SMS.',
                'details' => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }
}