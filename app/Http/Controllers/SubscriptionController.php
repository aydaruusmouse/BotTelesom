<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    // Check subscription
    public function checkSubscription(Request $request)
    {
        
        $validatedData = $request->validate([
            'msisdn' => 'required|string|', // Ensure a valid phone number format (allows "+" at the start)
            'offer' => 'required|string|max:10',
        ]);
        $msisdn = $validatedData['msisdn'];
        $offer = $validatedData['offer'];
        // show as a echo
    
        // Log the original MSISDN to see if it's coming in with the 'whatsapp:' prefix
        Log::info('Original MSISDN received:', ['msisdn' => $validatedData['msisdn']]);
    
        // Trim spaces, remove whatsapp: prefix, then sanitize
        $msisdn = str_replace('whatsapp:', '', $validatedData['msisdn']);
        Log::info('MSISDN after removing whatsapp:', ['msisdn' => $msisdn]);
    
        // Remove any non-numeric characters
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);
        Log::info('Sanitized MSISDN after removing non-numeric characters:', ['msisdn' => $msisdn]);
    
        try {
            // Make the HTTP request with sanitized MSISDN
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
            ])->post('http://172.16.53.106:8080/sdf/web/subscription/check-subscription', [
                'msisdn' => $msisdn,
                'offer' => $validatedData['offer'],
            ]);
    
            // Return response data from external API
            return response()->json($response->json());
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while checking the subscription.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    

    // Subscribe to an offer
    public function subscribe(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'msisdn' => 'required|string|', // Ensure a valid phone number format (allows "+" at the start)
            'offer' => 'required|string|max:10', // Ensure the offer string meets requirements
        ]);

        // Sanitize the MSISDN (remove 'whatsapp:' prefix if present, and non-numeric characters)
        $msisdn = preg_replace('/[^0-9]/', '', str_replace('whatsapp:', '', $validatedData['msisdn']));

        // Log the sanitized MSISDN
        Log::info('Sanitized MSISDN for subscribe:', ['msisdn' => $msisdn]);

        try {
            // Make the HTTP request with sanitized MSISDN
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
            ])->post('http://172.16.53.106:8080/sdf/web/subscription/subscribe', [
                'msisdn' => $msisdn,
                'offer' => $validatedData['offer'],
            ]);

            // Check for API errors or unexpected statuses
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => $response->json('message') ?? 'Subscription failed.',
                    'errorCode' => $response->json('errorCode') ?? 'unknown_error',
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => $response->json('message') ?? 'Subscribed successfully.',
                'data' => $response->json(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while subscribing.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    // Unsubscribe from an offer
    public function unsubscribe(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'msisdn' => 'required|string|', // Ensure a valid phone number format (allows "+" at the start)
            'offer' => 'required|string|max:10', // Ensure the offer string meets requirements
        ]);

        // Sanitize the MSISDN (remove 'whatsapp:' prefix if present, and non-numeric characters)
        $msisdn = preg_replace('/[^0-9]/', '', str_replace('whatsapp:', '', $validatedData['msisdn']));

        // Log the sanitized MSISDN
        Log::info('Sanitized MSISDN for unsubscribe:', ['msisdn' => $msisdn]);

        try {
            // Make the HTTP request with sanitized MSISDN
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
            ])->post('http://172.16.53.106:8080/sdf/web/subscription/unsubscribe', [
                'msisdn' => $msisdn,
                'offer' => $validatedData['offer'],
            ]);

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while unsubscribing.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
