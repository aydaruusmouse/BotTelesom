<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
    // Check subscription
    public function checkSubscription(Request $request)
    {
        try {
            // Make the HTTP request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
            ])->post('http://172.16.53.106:8080/sdf/web/subscription/check-subscription', $request->all());

            // If the request is successful, return the response data
            $responseData = $response->json();

            return response()->json($responseData);

        } catch (ConnectionException $e) {
            // Connection error handling
            return response()->json([
                'error' => 'An error occurred while checking the subscription.',
                'details' => $e->getMessage(),  // cURL error message
            ], 500);
        } catch (RequestException $e) {
            // Other HTTP request errors (like 404, 500)
            return response()->json([
                'error' => 'An HTTP error occurred.',
                'details' => $e->getMessage(),  // Request error message
            ], 500);
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    public function subscribe(Request $request)
    {
        // Validate input to ensure required fields are present
        $validatedData = $request->validate([
            'msisdn' => 'required|string|min:10|max:15', // Example validation
            'offer' => 'required|string|max:10',
        ]);
    
        try {
            // Make the HTTP request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
            ])->post('http://172.16.53.106:8080/sdf/web/subscription/subscribe', $validatedData);
    
            // Check for API errors or unexpected statuses
            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => $response->json('message') ?? 'Subscription failed.',
                    'errorCode' => $response->json('errorCode') ?? 'unknown_error',
                ], $response->status());
            }
    
            // Return successful response
            return response()->json([
                'success' => true,
                'message' => $response->json('message') ?? 'Subscribed successfully.',
                'data' => $response->json(),
            ], 200);
    
        } catch (ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while subscribing.',
                'details' => $e->getMessage(),
            ], 500);
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'An HTTP error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
 // Unsubscribe
 public function unsubscribe(Request $request)
{
    // Validate the request input
    $validatedData = $request->validate([
        'msisdn' => 'required|string|min:10|max:15', // Ensures a valid phone number format
        'offer' => 'required|string|max:10', // Ensures the offer string meets requirements
    ]);

    try {
        // Make the HTTP request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
        ])->post('http://172.16.53.106:8080/sdf/web/subscription/unsubscribe', $validatedData);

        // Return the response JSON from the external API
        return response()->json($response->json(), $response->status());

    } catch (ConnectionException $e) {
        // Handle connection error
        return response()->json([
            'error' => 'An error occurred while unsubscribing.',
            'details' => $e->getMessage(),  // cURL error message
        ], 500);
    } catch (RequestException $e) {
        // Handle HTTP error
        return response()->json([
            'error' => 'An HTTP error occurred.',
            'details' => $e->getMessage(),  // Request error message
        ], 500);
    } catch (\Exception $e) {
        // Catch any other unexpected errors
        return response()->json([
            'error' => 'An unexpected error occurred.',
            'details' => $e->getMessage(),
        ], 500);
    }
}
}