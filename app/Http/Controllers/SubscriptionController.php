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

 // Subscribe
 public function subscribe(Request $request)
 {
     try {
         // Make the HTTP request
         $response = Http::withHeaders([
             'Content-Type' => 'application/json',
             'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
         ])->post('http://172.16.53.106:8080/sdf/web/subscription/subscribe', $request->all());

         // Return the response JSON from the external API
         return response()->json($response->json(), $response->status());

     } catch (ConnectionException $e) {
         // Handle connection error
         return response()->json([
             'error' => 'An error occurred while subscribing.',
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

 // Unsubscribe
 public function unsubscribe(Request $request)
 {
     try {
         // Make the HTTP request
         $response = Http::withHeaders([
             'Content-Type' => 'application/json',
             'Authorization' => 'Basic ZGF0YXRyYW5zZmVyOmRhdGFAMTIz',
         ])->post('http://172.16.53.106:8080/sdf/web/subscription/unsubscribe', $request->all());

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
