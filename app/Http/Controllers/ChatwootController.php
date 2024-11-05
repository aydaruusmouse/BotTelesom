<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatwootController extends Controller
{
    private $chatwootBaseUrl = 'https://app.chatwoot.com/api/v1/accounts/106232';
    private $apiAccessToken= "o2FMxC3vHsPguvRvEfW5QuLP";

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

    $contactId = null; // Initialize contactId

    // Check if contact exists
    try {
        $existingContactResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
            ->get("{$this->chatwootBaseUrl}/contacts?phone_number={$phoneNumber}");

        if ($existingContactResponse->successful() && $existingContactResponse->json()['payload']) {
            // Contact exists
            $contactId = $existingContactResponse->json()['payload'][0]['id'];
        } else {
            // Create a new contact
            $contactResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
                ->post("{$this->chatwootBaseUrl}/contacts", [
                    'name' => $contactName,
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

    // Create a conversation
    try {
        $conversationResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
            ->post("{$this->chatwootBaseUrl}/conversations", [
                'inbox_id' => 50471,  // Replace with your actual inbox ID
                'source_id' => $sourceId,
                'contact_id' => $contactId,
            ]);

        if (!$conversationResponse->successful()) {
            \Log::error("Failed to create conversation: ", $conversationResponse->json());
            return response()->json(['success' => false, 'message' => 'Failed to create conversation.'], 400);
        }

        $conversationId = $conversationResponse->json()['id'];

        // Send a message
        $messageResponse = Http::withHeaders(['api_access_token' => $this->apiAccessToken])
            ->post("{$this->chatwootBaseUrl}/conversations/{$conversationId}/messages", [
                'content' => "Hello, Aidarus! How can I assist you today?",
                'message_type' => 'outgoing'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'conversation_id' => $conversationId,
            'message_id' => $messageResponse->json()['id']
        ]);
    } catch (\Exception $e) {
        \Log::error("Error creating conversation or sending message: " . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error creating conversation or sending message.'], 500);
    }
}


public function receiveIncomingMessage(Request $request)
{
    // Log the incoming message details
    \Log::info("Received incoming message:", $request->all());

    // Extract relevant details from the incoming message
    $incomingMessage = json_decode($request->getContent(), true);
    $content = $incomingMessage['content'] ?? 'No content'; // Fallback if content is missing
    $senderId = $incomingMessage['sender']['id'] ?? 'Unknown Sender'; // Assuming this is included in the payload
    $senderName = $incomingMessage['sender']['name'] ?? 'Unknown Sender'; // Fallback if name is missing

    // Optionally, you can save the incoming message to your database or trigger any other action

    return response()->json([
        'success' => true,
        'content' => $content,
        'sender_id' => $senderId,
        'sender_name' => $senderName,
    ]);
}


}
