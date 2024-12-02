<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class USSDController extends Controller
{
    public function showMenuForm()
    {
        // Generate a session ID and pass it to the view
        $sessionId = $this->generateSessionId();
        return view('menu', compact('sessionId'));
    }

    public function generateSessionId()
    {
        return mt_rand(100000000000, 999999999999);  // Generate a 12-digit random number
    }

    public function initiateUssdSession(Request $request)
{
    $msisdn = $this->cleanMsisdn($request->input('msisdn'));

    // Validate MSISDN
    if (empty($msisdn) || strlen($msisdn) < 10) {
        return response()->json(['error' => 'Invalid MSISDN format'], 400);
    }

    // Generate the 12-digit session ID
    $sessionId = $this->generateSessionId();
    $imsi = '123';
    $code = '*406#';
    $service = '406';

    // Perform the first cURL request with empty data
    $response1 = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('http://172.16.53.109:8083/ussd-plus-plus/web/ussd-menu', [
        'action' => 'continue',
        'service' => $service,
        'code' => $code,
        'sessionid' => $sessionId,
        'imsi' => $imsi,
        'msisdn' => $msisdn,
        'data' => '',  // Empty data for the first request
    ]);

    // Check if the first request was successful
    if ($response1->failed()) {
        return response()->json(['error' => 'Failed to initiate session'], 500);
    }

    // Now process the second cURL request with user data
    $userData = $request->input('data'); // User input data (1, 2, 3, or 4)

    // Ensure the user provided valid data (1, 2, 3, or 4)
    if (!in_array($userData, ['1', '2', '3', '4'])) {
        return response()->json(['error' => 'Invalid data input. Must be 1, 2, 3, or 4.'], 400);
    }

    // Perform the second cURL request with the user data
    $response2 = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('http://172.16.53.109:8083/ussd-plus-plus/web/ussd-menu', [
        'action' => 'continue',
        'service' => $service,
        'code' => $code,
        'sessionid' => $sessionId,  // Same session ID as the first request
        'imsi' => $imsi,
        'msisdn' => $msisdn,
        'data' => $userData,  // User-provided data
    ]);

    // Check if the second request was successful
    if ($response2->failed()) {
        return response()->json(['error' => 'Failed to continue session'], 500);
    }

    // Extract the data field from the response
    $responseData = $response2->json();

    // Filter to keep only the specific line you want
    $filteredData = '';
    if (isset($responseData['data'])) {
        // Split the data into lines
        $lines = explode("\n", $responseData['data']);
        foreach ($lines as $line) {
            // Keep the line containing "Hadhagaagu hadda waa:"
            if (strpos($line, 'Hadhagaagu hadda waa:') !== false) {
                $filteredData = $line;
                break;
            }
        }
    }

    // Return the filtered response
    return response()->json(['data' => $filteredData]);
}


    private function cleanMsisdn($msisdn)
    {
        $msisdn = str_replace('whatsapp:', '', $msisdn); // Remove "whatsapp:" prefix
        $msisdn = str_replace('+', '', $msisdn); // Remove any '+' signs
        $msisdn = preg_replace('/^\D/', '', $msisdn); // Remove any non-digit characters from the beginning
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn); // Remove any non-digit characters
        $msisdn = trim($msisdn); // Trim any whitespace and extra data

        return $msisdn;
    }
}

