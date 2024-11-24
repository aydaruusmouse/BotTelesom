<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class USSDController extends Controller
{
    public function showMenuForm()
    {
        return view('menu'); // Display the initial menu form
    }

    // Function to generate a 12-digit session ID
    public function generateSessionId()
    {
        return mt_rand(100000000000, 999999999999);  // Generate a 12-digit random number
    }

    // Function to handle the first cURL request with empty data
    public function initiateUssdSession(Request $request)
    {
        // Extract and clean the msisdn from the request
        $msisdn = $request->input('msisdn');

        // Clean the msisdn by removing "whatsapp:" prefix and any extra spaces
        $msisdn = str_replace('whatsapp:', '', $msisdn); // Remove "whatsapp:" prefix
        $msisdn = str_replace('+', '', $msisdn); // Remove any '+' signs
        $msisdn = preg_replace('/^\D/', '', $msisdn); // Remove any non-digit characters from the beginning
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn); // Remove any non-digit characters
        $msisdn = trim($msisdn); // Trim any whitespace and extra data like commas

        // Validate MSISDN
        if (empty($msisdn) || strlen($msisdn) < 10) {
            return response()->json(['error' => 'Invalid MSISDN format'], 400);  // Ensure valid length and non-empty value
        }

        // Generate the 12-digit session ID
        $sessionId = $this->generateSessionId();

        // Example input values (this should come from the request or user input)
        $imsi = '123';
        $code = '*406#';
        $service = '406';

        // Perform the first cURL request with empty data
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('http://172.16.53.109:8083/ussd-plus-plus/web/ussd-menu', [
            'action' => 'continue',
            'service' => $service,
            'code' => $code,
            'sessionid' => $sessionId,
            'imsi' => $imsi,
            'msisdn' => $msisdn,
            'data' => '',  // First request with empty data
        ]);

        // Handle the response (you can return it or process as needed)
        return $response->json();
    }

    // Function to handle the second cURL request with user input data
    public function continueUssdSession(Request $request)
    {
        // Retrieve the session ID and user input data from the request
        $sessionId = $request->input('sessionid');
        $data = $request->input('data'); // Data entered by the user (e.g., "1")
        
        // Extract and clean the msisdn from the request
        $msisdn = $request->input('msisdn');
        $msisdn = str_replace('whatsapp:', '', $msisdn); // Remove "whatsapp:" prefix
        $msisdn = str_replace('+', '', $msisdn); // Remove any '+' signs
        $msisdn = preg_replace('/^\D/', '', $msisdn); // Remove any non-digit characters from the beginning
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn); // Remove any non-digit characters
        $msisdn = trim($msisdn); // Trim any whitespace and extra data

        // Validate MSISDN
        if (empty($msisdn) || strlen($msisdn) < 10) {
            return response()->json(['error' => 'Invalid MSISDN format'], 400);
        }

        // Validate data (e.g., it should be one of the options like "1", "2", etc.)
        if (empty($data)) {
            return response()->json(['error' => 'Data is required for the second request'], 400);
        }

        // Default values (can be changed based on user request)
        $service = '406';
        $code = '*406#';
        $imsi = '123';

        // Perform the second cURL request with user data
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('http://172.16.53.109:8083/ussd-plus-plus/web/ussd-menu', [
            'action' => 'continue',
            'service' => $service,
            'code' => $code,
            'sessionid' => $sessionId,  // Use the same session ID from the first request
            'imsi' => $imsi,
            'msisdn' => $msisdn,
            'data' => $data,  // Data passed by the user
        ]);

        // Handle the response (you can return it or process as needed)
        return $response->json();
    }


}
