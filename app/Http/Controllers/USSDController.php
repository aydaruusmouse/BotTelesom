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

    public function processUSSD(Request $request)
{
    // Validate incoming data
    $request->validate([
        'msisdn' => 'required|regex:/^\+?[1-9]\d{1,14}$/', // Valid phone number (E.164 format)
        'data' => 'required|string',
    ]);

    // Data to be sent to the API
    $apiData = [
        'action' => 'continue',
        'service' => '406',
        'code' => '*406#',
        'sessionid' => uniqid(), // Example of generating a session ID dynamically
        'imsi' => '123', // Example IMSI
        'msisdn' => $request->input('msisdn'), // Phone number from user input
        'data' => $request->input('data'), // Data from user input
    ];

    try {
        // Call the external API
        $response = Http::timeout(10)->post('http://172.16.53.109:8083/ussd-plus-plus/web/ussd-menu', $apiData);

        // Parse the response from the API
        $responseData = $response->json();

        // Return JSON data to the browser
        return response()->json([
            'success' => true,
            'data' => $responseData,
            'message' => 'Data processed successfully',
        ]);
    } 
    catch (\Exception $e) {
        // Handle other errors
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage(),
        ]);
    }
}

}
