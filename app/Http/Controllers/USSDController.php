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

    if ($response1->failed()) {
        return response()->json(['error' => 'Failed to initiate session'], 500);
    }

    $userData = $request->input('data'); // User input data (1, 2, 3, or 4)

    if (!in_array($userData, ['1', '2', '3', '4'])) {
        return response()->json(['error' => 'Invalid data input. Must be 1, 2, 3, or 4.'], 400);
    }

    $response2 = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('http://172.16.53.109:8083/ussd-plus-plus/web/ussd-menu', [
        'action' => 'continue',
        'service' => $service,
        'code' => $code,
        'sessionid' => $sessionId,
        'imsi' => $imsi,
        'msisdn' => $msisdn,
        'data' => $userData,
    ]);

    if ($response2->failed()) {
        return response()->json(['error' => 'Failed to continue session'], 500);
    }

    $responseData = $response2->json();

    // Process the data to remove anything after the first line
    $cleanedData = '';
    if (isset($responseData['data'])) {
        // Split by ".\n" and take the first part
        $cleanedData = explode(".\n", $responseData['data'])[0] . '.';
    }

    // Return the processed response
    return response()->json(['data' => $cleanedData]);
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
