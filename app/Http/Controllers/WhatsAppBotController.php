<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppBotController extends Controller
{
    protected $sessionData = [];
    // for the test web 
    
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
            "0. Go Back",
        ];
        
        $fiberSubMenu = [
            "1. New Fiber",
            "2. Internet Billing",
            "3. Troubleshooting",
            "0. Go Back",
        ];
        
        $newFiber = [
            "1. Hargeisa = HRG",
            "2. Burco = BRO",
            "3. Berbera = BER",
            "4. Boorama = BRM",
            "5. Wajaale = WAJ",
            "6. Buuhoodle = BUH",
            "7. Gabiley = GAB",
            "8. Laascaanood = LAS",
            "0. Go Back",
        ];
        
        $internetSpeed = [
            "1. 5MB  $20 Monthly",
            "2. 7MB  $30 Monthly",
            "3. 15MB $50 Monthly",
            "4. 20MB $80 Monthly",
            "5. 35MB $150 Monthly",
            "6. More than the above speed",
            "0. Go Back",
        ];
        
        $simCardSubMenu = [
            "1. Mushaax",
            "2. Pin/Puk",
            "3. Telesom Services",
            "0. Go Back",
        ];
        
        $valueAddedServicesSubMenu = [
            "1. My Status",
            "2. Mobile Education",
            "3. Mobile Market",
            "4. Who is calling me",
            "5. SMS Groupy",
            "6. MWoman",
            "7. Antitheft",
            "0. Go Back",
        ];
        
        $selfSupportSubMenu = [
            "1. Activate/Deactivate Roaming Services",
            "2. Pin/Puk",
            "3. Data Mifi/Super Mifi Balance",
            "4. Prepaid Balance",
            "5. Internet Balance",
            "6. Kaafiye Balance",
            "7. Bonus Balance",
            "8. Wrong ZAAD, EVC, and Data Transfer",
            "9. Unblock Yourself",
            "0. Go Back",
        ];
        
        $additionalServicesSubMenu = [
            "1. Connect with Agent Live",
            "2. Recommended Offers",
            "0. Go Back",
        ];
        
        $customerSatisfactionSubMenu = [
            "1. Very Good",
            "2. Good",
            "3. Okay",
            "4. Bad",
            "5. Very Bad",
            "0. Go Back",
        ];
    
        // Default greeting and main menu
        if (in_array($userMessage, ['hi', 'hello', 'morning', 'good morning', 'asc'])) {
            $responseMessage = "Good MORNING, Khalid! Please choose what we can help with today:<br>";
            $responseMessage .= implode("<br>", $mainMenu);
            $_SESSION['menu_state'] = 'main'; // Initialize state to main
    
        } elseif ($_SESSION['menu_state'] === 'main') {
            // Handle main menu selections
            switch ($userMessage) {
                case '1':
                    $responseMessage = "You have chosen ZAAD services. Please select an option:<br>" . implode("<br>", $zaadSubMenu);
                    $_SESSION['menu_state'] = 'zaad'; // Set state to zaad
                    break;
                case '2':
                    $responseMessage = "You have chosen Internet services. Please select an option:<br>" . implode("<br>", $internetSubMenu);
                    $_SESSION['menu_state'] = 'internet'; // Set state to zaad
                    break;
                case '3':
                    $responseMessage = "You have chosen Sim Card services. Please select an option:<br>" . implode("<br>", $simCardSubMenu);
                    $_SESSION['menu_state'] = 'sim_card'; // Set state to zaad
                    break;
                case '4':
                    $responseMessage = "Value Added Services
                    . Please select an option:<br>" . implode("<br>", $valueAddedServicesSubMenu);
                    $_SESSION['menu_state'] = 'VAS'; // Set state to zaad
                    break;
                case '5':
                    $responseMessage = "Self Support
                    . Please select an option:<br>" . implode("<br>", $selfSupportSubMenu);
                    $_SESSION['menu_state'] = 'selfsupport'; // Set state to zaad
                    break;
                case '6':
                    $responseMessage = "Additional Services. Please select an option:<br>" . implode("<br>", $additionalServicesSubMenu);
                    $_SESSION['menu_state'] = 'additional service'; // Set state to zaad
                    break;
                case '7':
                    $responseMessage = "Customer Satisfaction Please select an option:<br>" . implode("<br>", $customerSatisfactionSubMenu);
                    $_SESSION['menu_state'] = 'additional service'; // Set state to zaad
                    break;
                case '8':
                    $responseMessage = "You have Connect Live Agent . :<br>" . implode("<br>",);
                    $_SESSION['menu_state'] = 'additional service'; // Set state to zaad
                    break;
                // Additional handling for other main menu options...
                default:
                    $responseMessage = "Sorry, I didn’t understand that. Please type 'hi' or 'hello' to start again.";
            }
        } elseif ($_SESSION['menu_state'] === 'zaad') {
            // Handle ZAAD submenu selections directly
            switch ($userMessage) {
                case '1': // New ZAAD Account
                    $responseMessage = "Dear Mr/Ms. If you want to open a new ZAAD account, one of the following documents is needed:<br>"
                                     . "National ID<br>Driver's License<br>Passport<br>"
                                     . "Please visit the nearest Telesom branch or call 151.<br>"
                                     . "For more information, visit our website: Zaad Services - Telesom telecom";
                    break;
                case '2': // New Merchant Account
                    $responseMessage = "Dear Mr/Ms. If you want to open a new ZAAD Merchant account, one of the following documents is needed:<br>"
                                     . "Business license<br>Another Merchant<br>Telesom staff<br>"
                                     . "Please visit the nearest Telesom branch or call 522387.<br>"
                                     . "For more information, visit our website: https://telesom.com/bussiness/financial_services";
                    break;
                case '3': // Wrong ZAAD Transfer Support
                    $responseMessage = "Did you send the money from your WhatsApp number?<br>1. Yes<br>2. No";
                    $_SESSION['menu_state'] = 'zaad_wrong_transfer'; // Set state for handling wrong transfer
                    break;
                case '4': // Last ZAAD Transactions
                    $responseMessage = "Dear {{User}}, follow the steps below to view your last ZAAD transactions:<br>"
                                     . "Dial *222# or *888#<br>2. Choose option 5 (Show my Last Transaction)<br>"
                                     . "You will then be able to view the available options.";
                    break;
                case '0': // Go back to main menu
                    
                    $_SESSION['menu_state'] = 'zaad';
                    $responseMessage = "You are still in the ZAAD services. Please select an option:<br>" . implode("<br>", $zaadSubMenu);
                    break;
                default:
                    $responseMessage = "Invalid option. Please choose a valid option from the ZAAD services menu.";
            }
        } elseif ($_SESSION['menu_state'] === 'zaad_wrong_transfer') {
            // Handle user response for sending money from WhatsApp
            if ($userMessage === '1') { // Yes
                $responseMessage = "Please choose the type of Money:<br>1. ZAAD Shilling<br>2. ZAAD Dollar<br>3. Airtime<br>4. Data Recharge";
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_type';
            } elseif ($userMessage === '2') { // No
                $responseMessage = "Please enter the number you sent the money from:";
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_sender_number';
            } else {
                $responseMessage = "Invalid option. Please respond with '1' for Yes or '2' for No.";
            }
        }
        
        // Handle type of money selection (for "Yes" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_type') {
            if ($userMessage === '1') { // ZAAD Shilling
                $responseMessage = "Please enter the receiver number:";
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_receiver_number';
                $_SESSION['money_type'] = 'ZAAD Shilling';
            } elseif ($userMessage === '2') { // ZAAD Dollar
                $responseMessage = "Please enter the receiver number:";
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_receiver_number';
                $_SESSION['money_type'] = 'ZAAD Dollar';
            } elseif ($userMessage === '3') { // Airtime
                $responseMessage = "Please enter the transaction number:";
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number';
                $_SESSION['money_type'] = 'Airtime';
            } elseif ($userMessage === '4') { // Data Recharge
                $responseMessage = "Please enter the transaction number:";
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number';
                $_SESSION['money_type'] = 'Data Recharge';
            } else {
                $responseMessage = "Invalid option. Please choose a valid type of money.";
            }
        }
        
        // Handle receiver number input (for "Yes" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_receiver_number') {
            $_SESSION['zaad_wrong_transfer_receiver_number'] = $userMessage; // Store receiver's number
            $responseMessage = "Please enter the transaction number:";
            $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number'; // Set state for transaction number
        }
        
        // Handle transaction number input (for "Yes" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_transaction_number') {
            // Finalize the wrong transfer process for "Yes" case
            $responseMessage = "Thank you! Your wrong transfer request has been submitted.<br>"
                             . "Money Type: " . $_SESSION['money_type'] . "<br>"
                             . "Receiver Number: " . $_SESSION['zaad_wrong_transfer_receiver_number'] . "<br>"
                             . "Transaction Number: " . $userMessage;
            $_SESSION['menu_state'] = 'zaad'; // Reset to ZAAD submenu after handling
        }
        
        // Handle sender number input (for "No" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_sender_number') {
            $_SESSION['sender_number'] = $userMessage; // Store sender's number
            $responseMessage = "Please choose the type of Money:<br>1. ZAAD Shilling<br>2. ZAAD Dollar<br>3. Airtime<br>4. Data Recharge";
            $_SESSION['menu_state'] = 'zaad_wrong_transfer_type_from_sender';
        }
        
        // Handle type of money selection (for "No" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_type_from_sender') {
            if ($userMessage === '1') { // ZAAD Shilling
                $responseMessage = "Please enter the number you sent the money to:";
                $_SESSION['money_type'] = 'ZAAD Shilling';
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_receiver_number_from_sender';
            } elseif ($userMessage === '2') { // ZAAD Dollar
                $responseMessage = "Please enter the number you sent the money to:";
                $_SESSION['money_type'] = 'ZAAD Dollar';
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_receiver_number_from_sender';
            } elseif ($userMessage === '3') { // Airtime
                $responseMessage = "Please enter the transaction number:";
                $_SESSION['money_type'] = 'Airtime';
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number_from_sender';
            } elseif ($userMessage === '4') { // Data Recharge
                $responseMessage = "Please enter the transaction number:";
                $_SESSION['money_type'] = 'Data Recharge';
                $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number_from_sender';
            } else {
                $responseMessage = "Invalid option. Please choose a valid type of money.";
            }
        }
        
        // Handle receiver number input (for "No" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_receiver_number_from_sender') {
            $_SESSION['receiver_number_from_sender'] = $userMessage; // Store the receiver number
            $responseMessage = "Please enter the transaction number:";
            $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number_from_sender';
        }
        
        // Handle transaction number input (for "No" case)
        if ($_SESSION['menu_state'] === 'zaad_wrong_transfer_transaction_number_from_sender') {
            // Finalize the wrong transfer process for "No" case
            $responseMessage = "Thank you! Your wrong transfer request has been submitted.<br>"
                             . "Money Type: " . $_SESSION['money_type'] . "<br>"
                             . "Receiver Number: " . $_SESSION['receiver_number_from_sender'] . "<br>"
                             . "Transaction Number: " . $userMessage;
            $_SESSION['menu_state'] = 'zaad'; // Reset to ZAAD submenu after handling
        }
       // Handle Ping/Buk number entry
       if ($_SESSION['menu_state'] === 'sim_card') {
        $_SESSION['menu_state'] = 'sim_card';
        if ($userMessage === 3) {
            $responseMessage = "Please enter the number you want to send money to:";
            $_SESSION['menu_state'] = 'Telesom_Services';

            # code...
        }
        if ($userMessage === '2') {
            $responseMessage = "Please enter your phone number for Ping/Buk:";
            $_SESSION['menu_state'] = 'ping_buk_number_entry'; // Set a new state to handle number entry
        } elseif ($userMessage === '0') {
            $responseMessage = "Going back to the main menu...";
            $_SESSION['menu_state'] = 'main';
            $responseMessage .= "<br>" . implode("<br>", $mainMenu);
        } 
    } 

        // Handle Pin/Puk number entry
        if ($_SESSION['menu_state']  === 'pin_puk_number_entry') {
            if (is_numeric($userMessage) && strlen($userMessage) === 9) {
                $this->sessionData[$from]['pin_puk_number'] = $userMessage;
                $apiResponse = $this->callPinPukAPI($this->sessionData[$from]['pin_puk_number']);
                
                if ($apiResponse['status'] === 'success') {
                    $responseMessage = "Pin/Puk details for number: " . $this->sessionData[$from]['pin_puk_number'] . "\nResponse: " . $apiResponse['message'];
                } else {
                    $responseMessage = "Error: " . $apiResponse['message'];
                }

                $this->sessionData[$from]['menu_state'] = 'sim_card'; // Return to Sim Card menu
                unset($this->sessionData[$from]['pin_puk_number']); // Reset stored number
            } else {
                $responseMessage = "Please enter a valid 9-digit number:";
            }
        }

        // Output the response message (for testing)
        echo $responseMessage;
    
        // Output response
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


    // handle the request from twillio side 
    public function handleIncomingMessage(Request $request)
    {
        $from = $request->input('From'); // Sender's number
        $userMessage = trim($request->input('Body')); // Message content

        \Log::info("Received message from {$from}: {$userMessage}");

        if (!isset($this->sessionData[$from])) {
            $this->sessionData[$from] = ['menu_state' => 'main']; // Initialize session data
        }

        $responseMessage = $this->processUserMessage($userMessage, $from);

        \Log::info("Response message for {$from}: {$responseMessage}");

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
            "0. Go Back",
        ];
        
        $fiberSubMenu = [
            "1. New Fiber",
            "2. Internet Billing",
            "3. Troubleshooting",
            "0. Go Back",
        ];
        
        $newFiber = [
            "1. Hargeisa = HRG",
            "2. Burco = BRO",
            "3. Berbera = BER",
            "4. Boorama = BRM",
            "5. Wajaale = WAJ",
            "6. Buuhoodle = BUH",
            "7. Gabiley = GAB",
            "8. Laascaanood = LAS",
            "0. Go Back",
        ];
        
        $internetSpeed = [
            "1. 5MB  $20 Monthly",
            "2. 7MB  $30 Monthly",
            "3. 15MB $50 Monthly",
            "4. 20MB $80 Monthly",
            "5. 35MB $150 Monthly",
            "6. More than the above speed",
            "0. Go Back",
        ];
        
        $simCardSubMenu = [
            "1. Mushaax",
            "2. Pin/Puk",
            "3. Telesom Services",
            "0. Go Back",
        ];
        
        $valueAddedServicesSubMenu = [
            "1. My Status",
            "2. Mobile Education",
            "3. Mobile Market",
            "4. Who is calling me",
            "5. SMS Groupy",
            "6. MWoman",
            "7. Antitheft",
            "0. Go Back",
        ];
        
        $selfSupportSubMenu = [
            "1. Activate/Deactivate Roaming Services",
            "2. Pin/Puk",
            "3. Data Mifi/Super Mifi Balance",
            "4. Prepaid Balance",
            "5. Internet Balance",
            "6. Kaafiye Balance",
            "7. Bonus Balance",
            "8. Wrong ZAAD, EVC, and Data Transfer",
            "9. Unblock Yourself",
            "0. Go Back",
        ];
        
        $additionalServicesSubMenu = [
            "1. Connect with Agent Live",
            "2. Recommended Offers",
            "0. Go Back",
        ];
        
        $customerSatisfactionSubMenu = [
            "1. Very Good",
            "2. Good",
            "3. Okay",
            "4. Bad",
            "5. Very Bad",
            "0. Go Back",
        ];
        // Default greeting and main menu
        if (in_array(strtolower($userMessage), ['hi', 'hello', 'morning', 'good morning', 'asc'])) {
            $responseMessage = "Good MORNING! Please choose what we can help with today:\n" . implode("\n", $mainMenu);
            $this->sessionData[$from]['menu_state'] = 'main';

        } elseif ($this->sessionData[$from]['menu_state'] === 'main') {
            // Handle main menu selections
            switch ($userMessage) {
                case '1': // ZAAD
                    $responseMessage = "You have chosen ZAAD services. Please select an option:\n" . implode("\n", $zaadSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'zaad';
                    break;
                case '2': // Internet
                    $responseMessage = "You have chosen Internet services. Please select an option:\n" . implode("\n", $internetSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'internet';
                    break;
                case '3': // Sim-card
                    $responseMessage = "You have chosen Sim Card services. Please select an option:\n" . implode("\n", $simCardSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                case '4': // Sim-card
                    $responseMessage = "Value Added Services
                    . Please select an option:\n" . implode("\n", $valueAddedServicesSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                case '5': // Sim-card
                    $responseMessage = "Self Support
                    . Please select an option:\n" . implode("\n", $selfSupportSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                case '6': // Sim-card
                    $responseMessage = "Additional Services. Please select an option:\n" . implode("\n", $additionalServicesSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                case '7': // Sim-card
                    $responseMessage = "Customer Satisfaction Please select an option:\n" . implode("\n", $customerSatisfactionSubMenu);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                case '8': // Sim-card
                    $responseMessage = "You have Connect Live Agent . :\n" . implode("\n",);
                    $this->sessionData[$from]['menu_state'] = 'sim_card';
                    break;
                default:
                    $responseMessage = "Sorry, I didn’t understand that. Please type 'hi' or 'hello' to start again.";
                    break;
            }
        } elseif ($this->sessionData[$from]['menu_state'] === 'zaad') {
            // Handle ZAAD submenu selections
            if ($userMessage === "1") {
                // Handle New ZAAD Account
                $responseMessage = "You have chosen to create a new ZAAD account. Please provide your information.";
            } elseif ($userMessage === "2") {
                // Handle Merchant Information
                $responseMessage = "You have chosen Merchant Information. Please specify your merchant details.";
            } elseif ($userMessage === "3") {
                // Handle Wrong ZAAD Transfer Support
                $responseMessage = "You have chosen Wrong ZAAD Transfer Support. Please describe your issue.";
            } elseif ($userMessage === "4") {
                // Handle Last ZAAD Transactions
                $responseMessage = "You have chosen Last ZAAD Transactions. Fetching your last transactions...";
            } elseif ($userMessage === "5") {
                // Handle Waafi
                $responseMessage = "You have chosen Waafi services. Please provide the details.";
            } elseif ($userMessage === "6") {
                // Handle Connect With Agent
                $responseMessage = "You have chosen to connect with an agent. Please hold on.";
            } elseif ($userMessage === "0") {
                // Go back option
                $this->sessionData[$from]['menu_state'] = 'main'; // Reset to main menu
                $responseMessage = "Going back to the main menu.";
            } else {
                // Handle invalid option
                $responseMessage = "Invalid option. Please select a valid submenu option.";
            }
                
        } elseif ($this->sessionData[$from]['menu_state'] === 'sim_card') {
            // Handle Sim-card submenu selections
            switch ($userMessage) {
                case '1':
                    $responseMessage = "Mushaax Information...";
                    break;
                case '2':
                    $responseMessage = "Please enter your phone number for Pin/Puk:";
                    $this->sessionData[$from]['menu_state'] = 'pin_puk_number_entry';
                    break;
                case '0':
                    $responseMessage = "Going back to the main menu...\n" . implode("\n", $mainMenu);
                    $this->sessionData[$from]['menu_state'] = 'main';
                    break;
                default:
                    $responseMessage = "Please select a valid option.";
            }
        }

        // Handle Pin/Puk number entry
        if ($this->sessionData[$from]['menu_state'] === 'pin_puk_number_entry') {
            if (is_numeric($userMessage) && strlen($userMessage) === 9) {
                $this->sessionData[$from]['pin_puk_number'] = $userMessage;
                $apiResponse = $this->callPinPukAPI($this->sessionData[$from]['pin_puk_number']);
                
                if ($apiResponse['status'] === 'success') {
                    $responseMessage = "Pin/Puk details for number: " . $this->sessionData[$from]['pin_puk_number'] . "\nResponse: " . $apiResponse['message'];
                } else {
                    $responseMessage = "Error: " . $apiResponse['message'];
                }

                $this->sessionData[$from]['menu_state'] = 'sim_card'; // Return to Sim Card menu
                unset($this->sessionData[$from]['pin_puk_number']); // Reset stored number
            } else {
                $responseMessage = "Please enter a valid 9-digit number:";
            }
        }

        return $responseMessage;
    }

    private function sendMessage($to, $message)
    {
        $accountSid = 'AC38dcca7bf336dcf27b4027f401338024';
        $authToken = '3ecd5a872109f5a99b4375e616335b32';
        $twilioNumber = 'whatsapp:+14155238886';

        $client = new Client($accountSid, $authToken);

        try {
            $client->messages->create($to, ['from' => $twilioNumber, 'body' => $message]);
        } catch (\Exception $e) {
            \Log::error('Failed to send message: ' . $e->getMessage());
        }
    }

    protected function callPinPukAPI($number)
    {
        return [
            'status' => 'success',
            'message' => 'Details for the requested number.'
        ];
    }
}
