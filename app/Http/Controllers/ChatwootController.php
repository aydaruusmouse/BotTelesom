<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatwootController extends Controller
{
    private $chatwootBaseUrl = 'https://app.chatwoot.com/api/v1/accounts/106232';
    private $apiAccessToken;

    public function __construct()
    {
        $this->apiAccessToken = config('services.chatwoot.api_access_token'); // Store your access token in config
    }

    public function sendMessage(Request $request)
{
    \Log::info("Received incoming and function reached:");
    \Log::info("API Access Token: " . $this->apiAccessToken);

    $contactName = $request->input('contactName', 'Aidarus Muse');
    $phoneNumber = $request->input('phoneNumber', '+252634671999');
    $sourceId = "whatsapp:{$phoneNumber}";

    $contactId = null; 
    $conversationId = null;

    // Check if contact exists
    try {
        $existingContactResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
            ->get("{$this->chatwootBaseUrl}/contacts?phone_number={$phoneNumber}");

        if ($existingContactResponse->successful() && !empty($existingContactResponse->json()['payload'])) {
            // Contact exists
            $contactId = $existingContactResponse->json()['payload'][0]['id'];
        } else {
            // Create a new contact
            $contactResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
                ->post("{$this->chatwootBaseUrl}/contacts", [
                    'name' => "Aidarus M",
                    'phone_number' => $phoneNumber
                ]);

            if ($contactResponse->successful()) {
                $contactId = $contactResponse->json()['payload']['contact']['id'];
            } else {
                \Log::error("Failed to create contact: ", $contactResponse->json());
                return response()->json(['success' => false, 'message' => 'Failed to create contact.'], 400);
            }
        }
    } catch (\Exception $e) {
        \Log::error("Error checking or creating contact: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error checking or creating contact.'], 500);
    }

    // Check if an existing conversation exists for the contact
    try {
        $existingConversationsResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
            ->get("{$this->chatwootBaseUrl}/conversations?contact_id={$contactId}");

        if ($existingConversationsResponse->successful() && !empty($existingConversationsResponse->json()['payload'])) {
            // Use the existing conversation
            $conversationId = $existingConversationsResponse->json()['payload'][0]['id'];
            \Log::info("Using existing conversation ID: " . $conversationId);
        } else {
            // Create a new conversation
            $conversationResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
                ->post("{$this->chatwootBaseUrl}/conversations", [
                    'inbox_id' => 50471,  
                    'source_id' => $sourceId,
                    'contact_id' => $contactId,
                ]);

            if ($conversationResponse->successful()) {
                $conversationId = $conversationResponse->json()['id'];
                \Log::info("Created new conversation ID: " . $conversationId);
            } else {
                \Log::error("Failed to create conversation: ", $conversationResponse->json());
                return response()->json(['success' => false, 'message' => 'Failed to create conversation.'], 400);
            }
        }

        // Send a message to the existing or newly created conversation
        $messageResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
            ->post("{$this->chatwootBaseUrl}/conversations/{$conversationId}/messages", [
                'content' => "Hello, Aidarus! How can I assist you today?",
                'message_type' => 'outgoing'
            ]);

        if ($messageResponse->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully.',
                'conversation_id' => $conversationId,
                'message_id' => $messageResponse->json()['id']
            ]);
        } else {
            \Log::error("Failed to send message: ", $messageResponse->json());
            return response()->json(['success' => false, 'message' => 'Failed to send message.'], 400);
        }
    } catch (\Exception $e) {
        \Log::error("Error checking for conversations or sending message: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error checking for conversations or sending message.'], 500);
    }
}


    


public function receiveIncomingMessage(Request $request)
{

    \Log::info("Received incoming message:", $request->all());

    // Extract relevant details from the incoming message
    $incomingMessage = json_decode($request->getContent(), true);
    $content = $incomingMessage['content'] ?? 'No content'; 
    $senderId = $incomingMessage['sender']['id'] ?? 'Unknown Sender'; 
    $senderName = $incomingMessage['sender']['name'] ?? 'Unknown Sender'; 

  
    return response()->json([
        'success' => true,
        'content' => $content,
        'sender_id' => $senderId,
        'sender_name' => $senderName,
    ]);
}



// public function handleIncomingMessage(Request $request)
// {
//     \Log::info("Incoming message received:");

//     // Check if the message is from the agent
//     $messageData = $request->json();

//     // Extract necessary data
//     $senderType = $messageData['sender_type'] ?? null;
//     $content = $messageData['content'] ?? 'No content available';
//     $conversationId = $messageData['conversation_id'] ?? null;

//     if ($senderType === 'User') {
//         \Log::info("Agent message content: " . $content);

//         // Process the agent's message as needed
//         // Here you can call any functions you need to handle this message

//         return response()->json(['success' => true]);
//     }

//     return response()->json(['success' => false, 'message' => 'Message not from agent.'], 400);
// }

}
