<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TroubleshootingController extends Controller
{
    public function showTroubleshootingForm()
    {
        return view('troubleshooting_form'); // Blade template for the form
    }

    public function requestTroubleshooting(Request $request)
    {
        // Validate using 'line_number'
        $request->validate([
            'msisdn' => 'required|string',
            'line_nubmer' => 'required|string',
            'service_type' => 'required|string|in:Internet,Line',
            'problem_type' => 'required|string|in:DSL,Line,Fiber,P2P',
        ]);
    
        try {
            // Use 'line_nubmer' in the API request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'apiTokenUser' => 'mob#!Billing!*',
                'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
            ])->post('http://10.10.0.7:8077/api/KaaliyeApi/RequestTroubleshooting', [
                'msisdn' => $request->input('msisdn'),
                'line_nubmer' => $request->input('line_nubmer'), 
                'service_type' => $request->input('service_type'),
                'problem_type' => $request->input('problem_type'),
            ]);            
    
            // Log the raw response body to verify its structure
        \Log::info('Raw API Response', ['body' => $response->body()]);

        // Decode the response body manually
        $decodedResponse = json_decode($response->body(), true);

        return response()->json([
            'status' => $decodedResponse['status'] ?? null,
            'message' => $decodedResponse['Message'] ?? null,
            'data' => $decodedResponse['Data'] ?? null,
        ]);
        } catch (\Exception $e) {
            \Log::error('Error in troubleshooting request: ' . $e->getMessage());
    
            return response()->json([
                'error' => 'An error occurred while processing the troubleshooting request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
}
