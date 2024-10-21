<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppBotController extends Controller
{
    protected $sessionData = [];
     public function testMessage(Request $request)
    {
        // Get the message sent by the user
        $userMessage = strtolower(trim($request->input('message')));
        $responseMessage = '';

        // Start the session if not already started
        if (!session_id()) {
            session_start();
        }
        
        // Define main menu and sub-menus
        $mainMenu = [
            '1. ZAAD',
            '2. Internet',
            '3. Troubleshooting',
            '4. Sim-card',
            '5. Self-support',
            '6. Connect with agent',
        ];

        // Sub-menus...
        $zaadSubMenu = [
            "1. New ZAAD Account (Information)",
            "2. Merchant (Information)",
            "3. Wrong ZAAD Transfer Support",
            "4. Last ZAAD Transactions",
            "0. Go Back",
        ];

        $internetSubMenu = [
            "1. New Fiber Service",
            "2. Internet Billing",
            "3. Troubleshooting",
            "0. Go Back",
        ];

        $simCardSubMenu = [
            "1. Mushaax",
            "2. Ping/buk",
            "3. Telesom Services",
            "0. Go Back",
        ];

        // Default greeting and main menu
        if (in_array($userMessage, ['hi', 'hello', 'morning', 'good morning', 'asc'])) {
            $responseMessage = "Good MORNING, Khalid! Please choose what we can help with today:<br>";
            $responseMessage .= implode("<br>", $mainMenu);
            $_SESSION['menu_state'] = 'main';

        } elseif ($_SESSION['menu_state'] === 'main') {
            // Handle main menu selections
            switch ($userMessage) {
                case '1':
                    $responseMessage = "You have chosen ZAAD services. Please select an option:<br>" . implode("<br>", $zaadSubMenu);
                    $_SESSION['menu_state'] = 'zaad';
                    break;
                case '2':
                    $responseMessage = "You have chosen Internet services. Please select an option:<br>" . implode("<br>", $internetSubMenu);
                    $_SESSION['menu_state'] = 'internet';
                    break;
                case '4':
                    $responseMessage = "You have chosen Sim Card services. Please select an option:<br>" . implode("<br>", $simCardSubMenu);
                    $_SESSION['menu_state'] = 'sim_card';
                    break;
                case '6':
                    $responseMessage = "Connecting you with an agent. Please hold on...";
                    $_SESSION['menu_state'] = 'main'; // Reset to main menu
                    break;
                default:
                    $responseMessage = "Sorry, I didn’t understand that. Please type 'hi' or 'hello' to start again.";
            }
        } 

        // Handle Ping/Buk number entry
        if ($_SESSION['menu_state'] === 'sim_card') {
            if ($userMessage === '2') {
                $responseMessage = "Please enter your phone number for Ping/Buk:";
                $_SESSION['menu_state'] = 'ping_buk_number_entry'; // Set a new state to handle number entry
            } elseif ($userMessage === '0') {
                $responseMessage = "Going back to the main menu...";
                $_SESSION['menu_state'] = 'main';
                $responseMessage .= "<br>" . implode("<br>", $mainMenu);
            }
        } 
        
        // Validate Ping/Buk phone number
        if ($_SESSION['menu_state'] === 'ping_buk_number_entry') {
            if (is_numeric($userMessage) && strlen($userMessage) === 9) {
                $_SESSION['ping_buk_number'] = $userMessage;

                // Call the API
                $apiResponse = $this->callPingBukAPI($_SESSION['ping_buk_number']);
                
                if ($apiResponse['status'] === 'success') {
                    $responseMessage = "Ping/Buk details for number: " . $_SESSION['ping_buk_number'] . "\nResponse: " . $apiResponse['message'];
                } else {
                    $responseMessage = "Error: " . $apiResponse['message'];
                }

                // Reset the session state after handling the request
                $_SESSION['menu_state'] = 'sim_card';
                unset($_SESSION['ping_buk_number']); // Remove the stored number
            } else {
                $responseMessage = "Please enter a valid 9-digit number:";
            }
            return back()->with('response', $responseMessage);
        }

        return back()->with('response', $responseMessage);
    }

    private function callPingBukAPI($phoneNumber)
    {
        // Prepare the cURL request to the Ping/Buk API
        $curl = curl_init();

        $postData = json_encode([
            "Callsub" => $phoneNumber,
            "UserId" => "imll",
        ]);

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

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            // Log the cURL error
            \Log::error("cURL Error: " . $err);
            return ['status' => 'error', 'message' => "cURL Error: " . $err];
        } else {
            // Log the raw API response for debugging
            \Log::info('API Response: ', ['response' => $response]);
            $decodedResponse = json_decode($response, true);
            
            // Check if the response is valid
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['status' => 'error', 'message' => 'Invalid JSON response.'];
            }

            // Check the status in the API response
            if (isset($decodedResponse['status'])) {
                // Check for success status
                if ($decodedResponse['status'] === 'success') {
                    return ['status' => 'success', 'message' => $decodedResponse['data']]; // Assuming 'data' contains the details
                } else {
                    return ['status' => 'error', 'message' => $decodedResponse['message']];
                }
            } else {
                return ['status' => 'error', 'message' => 'Invalid response structure.'];
            }
        }
    }
    public function handleIncomingMessage(Request $request)
    {
     
    $from = $request->input('From'); // Sender's number
    $userMessage = trim($request->input('Body')); // Message content

    // Debugging: Log incoming message
    \Log::info("Received message from {$from}: {$userMessage}");

    // Start the session if not already started
    if (!isset($this->sessionData[$from])) {
        $this->sessionData[$from] = ['menu_state' => 'main']; // Initialize session data
    }

    $responseMessage = $this->processUserMessage($userMessage, $from);

    // Debugging: Log response message
    \Log::info("Response message for {$from}: {$responseMessage}");

    // Send the response message back to the user via Twilio
    $this->sendMessage($from, $responseMessage);
    
    return response()->xml(['Message' => $responseMessage]);

    }

    protected function processUserMessage($userMessage, $from)
    {
        $mainMenu = [
            '1. ZAAD',
            '2. Internet',
            '3. Sim-card',
            '4. Value Added Services',
            '5. Self Support',
            '6. Additional Services',
            '7. Customer Satisfaction',
            '8. Connect with agent',
        ];

        $zaadSubMenu = [
            "1. New ZAAD Account (Information)",
            "2. Merchant (Information)",
            "3. Wrong ZAAD Transfer Support",
            "4. Last ZAAD Transactions",
            "5. Waafi",
            "6. Connect With Agent",
            "0. Go back"
        ];

        $internetSubMenu = [
            "1. Fiber",
            "2. Mobile broadband",
        ];
        // when select fiber
        $Fiber = [
          "1. New Fiber",
          "2. Internet Billing",
          "3. Troubleshooting",
          "0. Go Back",
        ];
     
        // New Fiber

        $newFiber=[
            "1. Hargeisa = HRG",
            "2. Burco = BRO",
            "3. Berbera = BER",
            "4. Boorama = BRM",
            "5. Wajaale = WAJ",
            "6. Buuhoodle : BUH",
            "7. Gabiley : GAB",
            "8. Laascaanood = LAS",
        ];

         $internetSpeed =[
           "1. 5MB  $20 Monthly     value = 20",
           "2 7MB $30 Monthly",
           "3 15MB $50 Monthly",
           "4 20MB $80 Monthly",
           "5 35MB $150 Monthly", 
           "6 More than the above speed",
         ];
        $simCardSubMenu = [
            "1. Mushaax",
            "2. Ping/buk",
            "3. Telesom Services",
            "0. Go Back",
        ];

        // Default greeting and main menu
        if (in_array(strtolower($userMessage), ['hi', 'hello', 'morning', 'good morning', 'asc'])) {
            $responseMessage = "Good MORNING! Please choose what we can help with today:\n" . implode("\n", $mainMenu);
            $this->sessionData[$from]['menu_state'] = 'main';

        } elseif ($this->sessionData[$from]['menu_state'] === 'main') {
            // Handle main menu selections
            switch ($userMessage) {
                case '1':
                    $responseMessage = "You have chosen ZAAD services. Please select an option:\n" . implode("\n", $zaadSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'zaad';
                    break;
                case '2':
                    $responseMessage = "You have chosen Internet services. Please select an option:\n" . implode("\n", $internetSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'internet';
                    break;
                case '4':
                    $responseMessage = "You have chosen Sim Card services. Please select an option:\n" . implode("\n", $simCardSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                case '6':
                    $responseMessage = "Connecting you with an agent. Please hold on...";
                    $this->sessionData[$from]['menu_state'] = 'main'; // Reset to main menu
                    break;
                default:
                    $responseMessage = "Sorry, I didn’t understand that. Please type 'hi' or 'hello' to start again.";
            }
        } elseif ($this->sessionData[$from]['menu_state'] === 'sim_card') {
            // Handle Ping/Buk number entry
            if ($userMessage === '2') {
                $responseMessage = "Please enter your phone number for Ping/Buk:";
                $this->sessionData[$from]['menu_state'] = 'ping_buk_number_entry'; // Set a new state to handle number entry
            } elseif ($userMessage === '0') {
                $responseMessage = "Going back to the main menu...\n" . implode("\n", $mainMenu);
                $this->sessionData[$from]['menu_state'] = 'main';
            }
        } 
        
        // Validate Ping/Buk phone number
        if ($this->sessionData[$from]['menu_state'] === 'ping_buk_number_entry') {
            if (is_numeric($userMessage) && strlen($userMessage) === 9) {
                $this->sessionData[$from]['ping_buk_number'] = $userMessage;

                // Call the API
                $apiResponse = $this->callPingBukAPI($this->sessionData[$from]['ping_buk_number']);
                
                if ($apiResponse['status'] === 'success') {
                    $responseMessage = "Ping/Buk details for number: " . $this->sessionData[$from]['ping_buk_number'] . "\nResponse: " . $apiResponse['message'];
                } else {
                    $responseMessage = "Error: " . $apiResponse['message'];
                }

                // Reset the session state after handling the request
                $this->sessionData[$from]['menu_state'] = 'sim_card';
                unset($this->sessionData[$from]['ping_buk_number']); // Remove the stored number
            } else {
                $responseMessage = "Please enter a valid 9-digit number:";
            }
        }

        return $responseMessage;
    }

    private function sendMessage($to, $message)
{
    // Twilio credentials
    $accountSid = 'AC38dcca7bf336dcf27b4027f401338024';
    $authToken = '3ecd5a872109f5a99b4375e616335b32';
    $twilioNumber = 'whatsapp:+14155238886'; 

    // Create a Twilio client
    $client = new Client($accountSid, $authToken);

    try {
        // Send a message back to the user
        $client->messages->create(
            $to, // Recipient's number
            [
                'from' => $twilioNumber,
                'body' => $message
            ]
        );
    } catch (\Exception $e) {
        \Log::error('Failed to send message: ' . $e->getMessage());
    }
}

    
}

