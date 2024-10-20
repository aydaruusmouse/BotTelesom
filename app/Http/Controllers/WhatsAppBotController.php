<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppBotController extends Controller
{
    protected $sessionData = [];

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
        
    }

    protected function callPinPukAPI($number)
    {
        return [
            'status' => 'success',
            'message' => 'Details for the requested number.'
        ];
    }
}
