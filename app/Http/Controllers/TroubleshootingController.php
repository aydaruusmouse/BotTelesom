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
            'line_number' => 'required|numeric',
            'service_type' => 'required|string|in:Internet,Line',
            'problem_type' => 'required|string|in:DSL,Line,Fiber,P2P',
        ]);
    
        try {
            // Use 'line_nubmer' in the API request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://10.10.0.7:8077/api/KaaliyeApi/RequestTroubleshooting', [
                'msisdn' => $request->input('msisdn'),
                'line_nubmer' => $request->input('line_number'), // Map validated 'line_number' to 'line_nubmer'
                'service_type' => $request->input('service_type'),
                'problem_type' => $request->input('problem_type'),
            ]);
    
            return response()->json([
                'status' => $response->json('status'),
                'message' => $response->json('Message'),
                'data' => $response->json('Data'),
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
