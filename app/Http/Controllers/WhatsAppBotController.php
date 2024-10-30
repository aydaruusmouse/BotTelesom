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
        $newFiber= [
            '1. New Internet ',
            '2. Internet Billing',
            '3. Troubleshooting'
        ];
        
        $fiberSubMenu = [
            "1. New Internet",
            "2. Internet Billing",
            "3. Troubleshooting",
            "0. Go Back",
        ];
        
        $city = [
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
            $_SESSION['menu_state'] = 'internet'; // Set state to internet
            break;
        case '3':
            $responseMessage = "You have chosen Sim Card services. Please select an option:<br>" . implode("<br>", $simCardSubMenu);
            $_SESSION['menu_state'] = 'sim_card'; // Set state to sim card
            break;
        case '4':
            $responseMessage = "Value Added Services. Please select an option:<br>" . implode("<br>", $valueAddedServicesSubMenu);
            $_SESSION['menu_state'] = 'VAS'; // Set state to VAS
            break;
        case '5':
            $responseMessage = "Self Support. Please select an option:<br>" . implode("<br>", $selfSupportSubMenu);
            $_SESSION['menu_state'] = 'selfsupport'; // Set state to self support
            break;
        case '6':
            $responseMessage = "Additional Services. Please select an option:<br>" . implode("<br>", $additionalServicesSubMenu);
            $_SESSION['menu_state'] = 'additional_service'; // Set state to additional service
            break;
        case '7':
            $responseMessage = "Customer Satisfaction. Please select an option:<br>" . implode("<br>", $customerSatisfactionSubMenu);
            $_SESSION['menu_state'] = 'customer_satisfaction'; // Set state to customer satisfaction
            break;
        case '8':
            $responseMessage = "Connecting to Live Agent...";
            // Add live agent state if needed.
            break;
        default:
            $responseMessage = "Sorry, I didn’t understand that. Please choose an option from the menu.";
            break;
    }
} elseif($_SESSION['menu_state'] === 'internet') {
    // Handle internet services selections
    switch ($userMessage) {
        case '1':
            $responseMessage = "You have chosen New Fiber services. Please select a location:<br>  " . implode("<br>", $fiberSubMenu);
            $_SESSION['menu_state'] = 'new_fiber'; // Update to 'new_fiber_location' state
            break;
        case '2':
            $responseMessage = 'You have in  Internet Billing';
            $_SESSION['menu_state'] = 'Internet_Billing';
            break;
        case '3':
            $responseMessage= 'In TroubleShooting';
            $_SESSION['menu_state'] = 'Troubleshooting';
            break;
        case '0': // Go back to main menu
            $responseMessage = "Going back to the main menu...";
            $_SESSION['menu_state'] = 'main'; // Reset to main menu
            $responseMessage .= "<br>" . implode("<br>", $mainMenu);
            break;

        default:
            $responseMessage = "Invalid option. Please choose from the Internet menu.";
            break;
    }

   
}


elseif ($_SESSION['menu_state']=== 'new_fiber') {
    if($userMessage === '1'){
        $responseMessage = "You have chosen New Fiber services. Please select a location:<br>  " . implode("<br>", $city);
        $_SESSION['menu_state'] = 'new_fiber_location';
    }elseif($userMessage === '2'){
        
        $_SESSION['menu_state'] = 'internet_billing'; // Set menu state to internet_billing

        $responseMessage = "Please enter the line you have registered for your internet (e.g., 522297):";

    }elseif($userMessage === '3') {
        $responseMessage = "Please select the type of troubleshooting you need:<br>1. Internet Services<br>2. Landline (Fixed Line)<br>0. Go Back";
        $_SESSION['menu_state'] = 'troubleshooting'; // Set menu state to troubleshooting
        
    }
   
    # code...
}elseif ($_SESSION['menu_state'] === 'internet_billing') {
    // Check if the user has entered a valid line number (e.g., it should be numeric)
    if (preg_match('/^\d{6}$/', $userMessage)) { // Assuming the line number is 6 digits
        // Here, we can retrieve the due balance based on the line number
        // For now, we will assume a static balance for demonstration
        // In a real application, you might query a database for the actual balance
        $dueBalance = 100; // Example static value
        
        $responseMessage = "Your due balance is $$dueBalance.";
        
        // Optionally, return to main menu or prompt further action
         // Reset to main menu
        $responseMessage .= "<br>Thank you! You can select another service:<br>";
    } else {
        $responseMessage = "Invalid line number. Please enter a valid 6-digit line number (e.g., 522297):";
    }
}// Handle internet services and landline troubleshooting

// Handle troubleshooting menu state
elseif ($_SESSION['menu_state'] === 'troubleshooting') {
    // Ask the user to select which type of troubleshooting (Internet or Landline)
    $responseMessage = "Please select the type of troubleshooting you need:<br>1. Internet Services<br>2. Landline (Fixed Line)<br>0. Go Back";
    $_SESSION['menu_state'] = 'troubleshooting_type';
}

// Handle type selection for troubleshooting
elseif ($_SESSION['menu_state'] === 'troubleshooting_type') {
    switch ($userMessage) {
        case '1': // Internet Services
            $responseMessage = "Please enter your Reference Number for Internet Services (e.g., 634220460):";
            $_SESSION['menu_state'] = 'internet_troubleshooting_reference'; // Update session state
            break;
        case '2': // Landline Services
            $responseMessage = "Please enter your Reference Number for Landline Services (e.g., 634220460):";
            $_SESSION['menu_state'] = 'landline_troubleshooting_reference'; // Update session state
            break;
        case '0': // Go Back
            $responseMessage = "Going back to the main menu...";
            $_SESSION['menu_state'] = 'main'; // Reset to main menu
            $responseMessage .= "<br>" . implode("<br>", $mainMenu);
            break;
        default:
            $responseMessage = "Invalid option. Please select from the troubleshooting menu.";
            break;
    }
}

// Handle reference number input for Internet Services troubleshooting
elseif ($_SESSION['menu_state'] === 'internet_troubleshooting_reference') {
    if (preg_match('/^\d{9}$/', $userMessage)) { // Assuming the reference number is 9 digits
        $_SESSION['internet_troubleshooting_reference'] = $userMessage; // Store the reference number

        // Display the list of available internet services based on the reference
        // Here you could dynamically retrieve the services, but for demonstration, we use static options
        $internetServices = [
            '1' => '510093 - DHOOL DIGITAL AGENCY FIBER - DSL',
            '2' => '518884 - LAVISH MEDSPA LOOKHEALTH DEDICATED'
        ];

        $responseMessage = "Please choose below your internet service:<br>" . implode("<br>", $internetServices);
        $_SESSION['menu_state'] = 'internet_troubleshooting_service'; // Move to service selection state
    } else {
        $responseMessage = "Invalid reference number. Please enter a valid 9-digit reference number (e.g., 634220460):";
    }
}

// Handle internet service selection for troubleshooting
elseif ($_SESSION['menu_state'] === 'internet_troubleshooting_service') {
    $internetServices = [
        '1' => '510093 - DHOOL DIGITAL AGENCY FIBER - DSL',
        '2' => '518884 - LAVISH MEDSPA LOOKHEALTH DEDICATED'
    ];

    if (array_key_exists($userMessage, $internetServices)) {
        $_SESSION['internet_troubleshooting_service'] = $internetServices[$userMessage]; // Store the selected service

        $responseMessage = "Troubleshooting request for Internet Service " . $_SESSION['internet_troubleshooting_service'] . " submitted successfully!";
        $_SESSION['menu_state'] = 'main'; // Reset to main menu
        $responseMessage .= "<br>Thank you! You can select another service:<br>" . implode("<br>", $mainMenu);
    } else {
        $responseMessage = "Invalid option. Please choose a valid internet service:<br>" . implode("<br>", $internetServices);
    }
}

// Handle reference number input for Landline Services troubleshooting
elseif ($_SESSION['menu_state'] === 'landline_troubleshooting_reference') {
    if (preg_match('/^\d{9}$/', $userMessage)) { // Assuming the reference number is 9 digits
        $_SESSION['landline_troubleshooting_reference'] = $userMessage; // Store the reference number

        // Display the list of available landline services based on the reference
        $landlineServices = [
            '1' => '510093 - DHOOL DIGITAL AGENCY FIBER',
            '2' => '518884 - LAVISH MEDSPA LOOKHEALTH DEDICATED'
        ];

        $responseMessage = "Please choose below your landline service:<br>" . implode("<br>", $landlineServices);
        $_SESSION['menu_state'] = 'landline_troubleshooting_service'; // Move to service selection state
    } else {
        $responseMessage = "Invalid reference number. Please enter a valid 9-digit reference number (e.g., 634220460):";
    }
}

// Handle landline service selection for troubleshooting
elseif ($_SESSION['menu_state'] === 'landline_troubleshooting_service') {
    $landlineServices = [
        '1' => '510093 - DHOOL DIGITAL AGENCY FIBER',
        '2' => '518884 - LAVISH MEDSPA LOOKHEALTH DEDICATED'
    ];

    if (array_key_exists($userMessage, $landlineServices)) {
        $_SESSION['landline_troubleshooting_service'] = $landlineServices[$userMessage]; // Store the selected service

        $responseMessage = "Troubleshooting request for Landline Service " . $_SESSION['landline_troubleshooting_service'] . " submitted successfully!";
        $_SESSION['menu_state'] = 'main'; // Reset to main menu
        $responseMessage .= "<br>Thank you! You can select another service:<br>" . implode("<br>", $mainMenu);
    } else {
        $responseMessage = "Invalid option. Please choose a valid landline service:<br>" . implode("<br>", $landlineServices);
    }
}


// Handle location selection
elseif ($_SESSION['menu_state'] === 'new_fiber_location') {
    $validLocations = [
        '1' => 'Hargeisa (HRG)',
        '2' => 'Burco (BRO)',
        '3' => 'Berbera (BER)',
        '4' => 'Boorama (BRM)',
        '5' => 'Wajaale (WAJ)',
        '6' => 'Buuhoodle (BUH)',
        '7' => 'Gabiley (GAB)',
        '8' => 'Laascaanood (LAS)'
    ];

    if (array_key_exists($userMessage, $validLocations)) {
        $_SESSION['new_fiber_location'] = $validLocations[$userMessage]; // Store selected location
        $responseMessage = "You have selected " . $_SESSION['new_fiber_location'] . ". Please enter your address (Max: 20 characters):";
        $_SESSION['menu_state'] = 'new_fiber_address'; // Move to address entry state
    } else {
        $responseMessage = "Invalid location. Please select a valid option:<br>" . implode("<br>", $validLocations);
    }
}

// Handle address input
elseif ($_SESSION['menu_state'] === 'new_fiber_address') {
    if (strlen($userMessage) <= 20) {
        $_SESSION['new_fiber_address'] = $userMessage; // Store entered address
        $responseMessage = "Please choose the speed of the Internet:<br>1. 5MB - $20 Monthly<br>2. 7MB - $30 Monthly<br>3. 15MB - $50 Monthly<br>4. 20MB - $80 Monthly<br>5. 35MB - $150 Monthly<br>6. More than the above speed";
        $_SESSION['menu_state'] = 'new_fiber_speed'; // Move to speed selection state
    } else {
        $responseMessage = "Address too long. Please enter an address with a maximum of 20 characters:";
    }
}

// Handle speed selection
elseif ($_SESSION['menu_state'] === 'new_fiber_speed') {
    $speedOptions = [
        '1' => '5MB - $20 Monthly',
        '2' => '7MB - $30 Monthly',
        '3' => '15MB - $50 Monthly',
        '4' => '20MB - $80 Monthly',
        '5' => '35MB - $150 Monthly',
        '6' => 'More than the above speed'
    ];

    if (array_key_exists($userMessage, $speedOptions)) {
        $_SESSION['new_fiber_speed'] = $speedOptions[$userMessage]; // Store selected speed
        $responseMessage = "You have selected " . $_SESSION['new_fiber_speed'] . ". Please enter your payment phone number (9 digits):";
        $_SESSION['menu_state'] = 'new_fiber_phone_number'; // Move to phone number state
    } else {
        $responseMessage = "Invalid speed selection. Please choose a valid speed option:<br>" . implode("<br>", $speedOptions);
    }
}

// Handle phone number entry
elseif ($_SESSION['menu_state'] === 'new_fiber_phone_number') {
    if (preg_match('/^\d{9}$/', $userMessage)) {
        $_SESSION['new_fiber_phone_number'] = $userMessage; // Store valid phone number
        $responseMessage = "The installation cost is $20. Please select your payment option:<br>1. Zaad Dollar<br>2. Zaad Shilling";
        $_SESSION['menu_state'] = 'new_fiber_payment'; // Move to payment selection state
    } else {
        $responseMessage = "Invalid phone number. Please enter a valid 9-digit phone number:";
    }
}

// Handle payment option selection
elseif ($_SESSION['menu_state'] === 'new_fiber_payment') {
    $paymentOptions = [
        '1' => 'Zaad Dollar',
        '2' => 'Zaad Shilling'
    ];

    if (array_key_exists($userMessage, $paymentOptions)) {
        $_SESSION['new_fiber_payment'] = $paymentOptions[$userMessage]; // Store selected payment option
        $responseMessage = "Dear customer, to get this service quickly, the Internet & Landline department will contact you. Thank you!";
        $_SESSION['menu_state'] = 'main'; // Reset to main menu after completion
    } else {
        $responseMessage = "Invalid payment option. Please choose a valid option:<br>1. Zaad Dollar<br>2. Zaad Shilling";
    }
}


elseif ($_SESSION['menu_state'] === 'zaad') {
    // Handle ZAAD submenu selections
    switch ($userMessage) {
        case '1': // New ZAAD Account
            $responseMessage = "To open a new ZAAD account, one of the following documents is needed:<br>National ID<br>Driver's License<br>Passport.<br>Visit the nearest Telesom branch or call 151.";
            break;
        case '2': // New Merchant Account
            $responseMessage = "To open a new ZAAD Merchant account, bring a Business License, another Merchant, or Telesom staff.<br>Visit the nearest Telesom branch or call 522387.";
            break;
        case '3': // Wrong ZAAD Transfer Support
            $responseMessage = "Did you send the money from your WhatsApp number?<br>1. Yes<br>2. No";
            $_SESSION['menu_state'] = 'zaad_wrong_transfer'; // Set state for wrong transfer
            break;
        case '4': // Last ZAAD Transactions
            $responseMessage = "To view your last ZAAD transactions, dial *222# or *888# and choose option 5 (Show Last Transaction).";
            break;
        case '0': // Go back to main menu
            $responseMessage = "Going back to the main menu...";
            $_SESSION['menu_state'] = 'main'; // Reset to main menu
            $responseMessage .= "<br>" . implode("<br>", $mainMenu);
            break;
        default:
            $responseMessage = "Invalid option. Please choose from the ZAAD menu.";
            break;
    }
} elseif ($_SESSION['menu_state'] === 'zaad_wrong_transfer') {
    // Handle wrong transfer submenu
    if ($userMessage === '1') {
        $responseMessage = "Please select the type of Money:<br>1. ZAAD Shilling<br>2. ZAAD Dollar<br>3. Airtime<br>4. Data Recharge";
        $_SESSION['menu_state'] = 'zaad_wrong_transfer_type'; // Set state for money type selection
    } elseif ($userMessage === '2') {
        $responseMessage = "Please enter the number you sent the money from:";
        $_SESSION['menu_state'] = 'zaad_wrong_transfer_sender_number'; // Set state for sender number entry
    } else {
        $responseMessage = "Invalid option. Please respond with '1' for Yes or '2' for No.";
    }
} elseif ($_SESSION['menu_state'] === 'zaad_wrong_transfer_type') {
    // Handle type of money selection
    if ($userMessage === '1' || $userMessage === '2') {
        $responseMessage = "Please enter the receiver's number:";
        $_SESSION['menu_state'] = 'zaad_wrong_transfer_receiver_number';
        $_SESSION['money_type'] = ($userMessage === '1') ? 'ZAAD Shilling' : 'ZAAD Dollar';
    } elseif ($userMessage === '3' || $userMessage === '4') {
        $responseMessage = "Please enter the transaction number:";
        $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number';
        $_SESSION['money_type'] = ($userMessage === '3') ? 'Airtime' : 'Data Recharge';
    } else {
        $responseMessage = "Invalid option. Please select the correct money type.";
    }
} elseif ($_SESSION['menu_state'] === 'zaad_wrong_transfer_receiver_number') {
    $_SESSION['zaad_wrong_transfer_receiver_number'] = $userMessage; // Store receiver's number
    $responseMessage = "Please enter the transaction number:";
    $_SESSION['menu_state'] = 'zaad_wrong_transfer_transaction_number'; // Set state for transaction number
} elseif ($_SESSION['menu_state'] === 'zaad_wrong_transfer_transaction_number') {
    // Finalize wrong transfer

    $apiRequestBody = [
        "msisdn" => '634097961',
        "transactionnumber" => $transactionNumber,
        "wrongnumber" => $wrongNumber,
        "currency_code" => $currencyCode
    ];
        // Convert the request body to JSON format
        $jsonRequestBody = json_encode($apiRequestBody);

        // Set up the API request headers
        $apiHeaders = [
            'apiTokenUser: mob#!Billing!*',
            'apiTokenPwd: De6$A7#ES282S@m@l!n.2BIoz',
            'Content-Type: application/json',
        ];
    
        // Initialize cURL session for the API request
        $ch = curl_init('http://10.10.0.7:8077/api/KaaliyeApi/BlockWrongTransaction');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequestBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $apiHeaders);
    
        // Execute the API request
        $apiResponse = curl_exec($ch);
    
        // Check for cURL errors
        if (curl_errno($ch)) {
            $responseMessage = "Error connecting to the API: " . curl_error($ch);
        } else {
            // Parse the API response
            $decodedResponse = json_decode($apiResponse, true);
    
            // Check for a successful response
            if ($decodedResponse['status'] == '1') {
                $responseMessage = $decodedResponse['Message']; // Display success message from the API
            } else {
                $responseMessage = "Failed to block the wrong transaction. Please try again.";
            }
        }
    
        // Close the cURL session
        curl_close($ch);
    
        // Reset the menu state to the main ZAAD menu after completing the API request
        $_SESSION['menu_state'] = 'zaad';
    
}





elseif($_SESSION['menu_state'] === 'sim_card') {
    if ($userMessage === '1') {
        $responseMessage = "mushaax";
        $_SESSION['menu_state'] = 'mushaax'; // Set state to handle Telesom services
    } elseif ($userMessage === '2') {
        $responseMessage = "Please enter your phone number for Ping/Buk:";
        $_SESSION['menu_state'] = 'ping_buk_number_entry'; // Set state for Ping/Buk number entry
    } elseif ($userMessage === '3') {
        $responseMessage = "You have selected Telesom Services. <br> Telesom Services- Below content will be send <br>
        “*444# Prepaid Internet u badalo <br>
        *400# Adeegyo casri ah <br>
        *141# Soo gudbinta Cabashooyinka <br>
        *151# Adeega Kaaliye <br>
        *122# Itus hadhaagayga <br>
        *662# Itus Hadhaaga Internetka <br>
        *133# Internet u dir Macmiil kale(Data Transfer) <br>
        *403# Adeega Xoogsade <br>
        *408# Adeega Ambassador service <br>
        *409# Iga jooji datada in aan ku isticmaalo Prepaidka iigu jira <br>
        151 Xafiiska daryeelka Macaamiisha Call center <br>
        150 Adeega Aqoonmaal <br>
        200 Adeega Aqoonmaal <br>
        212 Call me back service <br>
        Xafiiska daryeelka Macaamiisha Fadlan garaac 151 <br>
        Missed call Notification Fadlan garaac 126 <br>
        Adeeega Ilamaqal Fadlan garaac 118 <br>
        AdeegaVoice SMS Fadlan garaac 136  <br>
        Adeega Ila wadaag Fadlan garaac 115 <br>
        ";
        $_SESSION['menu_state'] = 'telesom_service_entry'; // Set state for Telesom service entry
    } elseif ($userMessage === '0') {
        $responseMessage = "Going back to the main menu...";
        $_SESSION['menu_state'] = 'main'; // Reset to main menu
        $responseMessage .= "<br>" . implode("<br>", $mainMenu); // Append main menu options
    } else {
        $responseMessage = "Invalid option. Please choose a valid option.";
    }
} 


        // Handle Pin/Puk number entry
       if ($_SESSION['menu_state'] === 'pin_puk_number_entry') {
    if (is_numeric($userMessage) && strlen($userMessage) === 9) {
        // Store the entered number
        $this->sessionData[$from]['pin_puk_number'] = $userMessage;
        
        // Call the Ping/Puk API function with the entered phone number
        $apiResponse = $this->callPingBukAPI($this->sessionData[$from]['pin_puk_number']);
        
        // Handle the API response
        if ($apiResponse['status'] === 'success') {
            $responseMessage = "Pin/Puk details for number: " . $this->sessionData[$from]['pin_puk_number'] . "\nResponse: " . $apiResponse['message'];
        } else {
            $responseMessage = "Error: " . $apiResponse['message'];
        }

        // Reset session state to 'sim_card' and clean up stored phone number
        $this->sessionData[$from]['menu_state'] = 'sim_card';
        unset($this->sessionData[$from]['pin_puk_number']); // Clear the stored number
    } else {
        // Validation for invalid input
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

    // Log the raw API response for debugging purposes
    \Log::info('API Response: ', ['response' => $response]);

    // Parse the API response
    $decodedResponse = json_decode($response, true);

    // Check if the JSON response is valid
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['status' => 'error', 'message' => 'Invalid JSON response.'];
    }

    // Check if the 'status' key exists in the response
    if (isset($decodedResponse['status'])) {
        // Handle a successful response
        if ($decodedResponse['status'] === 'success') {
            return ['status' => 'success', 'message' => $decodedResponse['data']]; // Assuming 'data' contains the response details
        } else {
            return ['status' => 'error', 'message' => $decodedResponse['message']]; // Return the error message from the API
        }
    } else {
        return ['status' => 'error', 'message' => 'Unexpected response structure.'];
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
