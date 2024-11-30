<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    // Check subscription
    public function checkSubscription(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'msisdn' => 'required|string|min:10|max:15', // Ensure a valid phone number format
            'offer' => 'required|string|max:10', // Ensure the offer string meets requirements
        ]);

        // Sanitize the MSISDN (remove any prefix and special characters)
        $msisdn = preg_replace('/[^0-9]/', '', str_replace('whatsapp:', '', $validatedData['msisdn']));

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
            'msisdn' => 'required|string|min:10|max:15', // Ensure a valid phone number format
            'offer' => 'required|string|max:10', // Ensure the offer string meets requirements
        ]);

        // Sanitize the MSISDN (remove any prefix and special characters)
        $msisdn = preg_replace('/[^0-9]/', '', str_replace('whatsapp:', '', $validatedData['msisdn']));

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
            'msisdn' => 'required|string|min:10|max:15', // Ensure a valid phone number format
            'offer' => 'required|string|max:10', // Ensure the offer string meets requirements
        ]);

        // Sanitize the MSISDN (remove any prefix and special characters)
        $msisdn = preg_replace('/[^0-9]/', '', str_replace('whatsapp:', '', $validatedData['msisdn']));

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
