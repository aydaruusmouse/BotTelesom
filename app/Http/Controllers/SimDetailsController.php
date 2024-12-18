<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimDetailsController extends Controller
{
    public function showForm()
    {
        return view('sim-details-form');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'phoneNumber' => 'required|numeric|digits:9', // Validate that the number is 9 digits long
        ]);

        $phoneNumber = $request->input('phoneNumber');
        
        // Call the API function with the entered phone number
        $apiResponse = $this->callPingBukAPI($phoneNumber);

        // Return the API response as JSON
        return response()->json($apiResponse);
    }

    private function callPingBukAPI($phoneNumber)
    {
        // Initialize the cURL session
        $curl = curl_init();

        // Prepare the data to be sent via POST
        $postData = json_encode([
            "Callsub" => $phoneNumber,
            "UserId" => "imll",
        ]);

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://10.55.1.143:8983/api/CRMApi/GetSimDetails",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "apiTokenUser: CRMUser",
                "apiTokenPwd: ZEWOALJNADSLLAIE321@!",
                "Content-Type: application/json"
            ],
        ]);

        // Execute the request and get the response
        $response = curl_exec($curl);
        $err = curl_error($curl);

        // Close the cURL session
        curl_close($curl);

        // Log the error and return an error message if there's a cURL error
        if ($err) {
            \Log::error("cURL Error: " . $err);
            return ['status' => 'error', 'message' => "cURL Error: " . $err];
        }

        // Parse the API response
        $decodedResponse = json_decode($response, true);

        // Check if the JSON response is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['status' => 'error', 'message' => 'Invalid JSON response.'];
        }

        // Check if the 'status' key exists in the response
        if (isset($decodedResponse['status'])) {
            // Handle a successful response
            if ($decodedResponse['status'] === '1') {
                // Success! Extract the first object from the message array
                $messageData = $decodedResponse['Data'][0] ?? null;

                return [
                    'status' => 'success',
                    'message' => $messageData
                ];
            } else {
                // Check if the 'Message' key exists
                $errorMessage = isset($decodedResponse['Message']) ? $decodedResponse['Message'] : 'No error message provided.';
                return [
                    'status' => 'error',
                    'message' => $errorMessage
                ];
            }
        } else {
            return ['status' => 'error', 'message' => 'Unexpected response structure.'];
        }
    }
}
