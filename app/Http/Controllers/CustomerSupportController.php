<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerSupportController extends Controller
{
    public function index()
    {
        // Render the main support view
        return view('support.index');
    }

    public function getReferences(Request $request)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'callsub' => 'required|string|max:10', // Adjust rules based on expected input
                'problem_type' => 'required|in:Internet,Line', // Must be 'Internet' or 'Line'
            ]);
    
            $callsub = $validated['callsub'];
            $problemType = $validated['problem_type'];
    
            // Default UserId
            $defaultUserId = 'imll';
    
            // Call the GetCustomerReferences API
            $response = Http::withHeaders([
                'apiTokenUser' => 'CRMUser',
                'apiTokenPwd' => 'ZEWOALJNADSLLAIE321@!',
            ])->timeout(10)->post('http://10.55.1.143:8983/api/CRMApi/GetCustomerReferences/?Status=Enabled', [
                'Callsub' => $callsub,
                'UserId' => $defaultUserId,
            ]);
    
            if ($response->successful()) {
                $decodedResponse = json_decode($response->body(), true);
    
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json(['error' => 'API returned invalid JSON', 'response' => $response->body()], 500);
                }
    
                // Get the raw data
                $data = $decodedResponse['Data'] ?? [];
                if (!is_array($data)) {
                    return response()->json($decodedResponse, 500);
                }
    
                // Filter based on problem type and status
                $filteredData = collect($data)->filter(function ($item) use ($problemType) {
                    // Ensure the item is enabled
                    if ($item['Status'] !== 'Enabled') {
                        return false;
                    }
    
                    // If "Internet" is selected, show only internet-related services
                    if ($problemType === 'Internet') {
                        return in_array($item['ServiceType'], ['DSL', 'FIBER', 'P2P']);
                    }
                    // If "Line" is selected, show only line-related services
                    elseif ($problemType === 'Line') {
                        return $item['ServiceType'] === 'LINE';
                    }
    
                    return false;
                });
    
                // Initialize a counter for creating dynamic service names
                $serviceCounter = 1;
                $formattedServices = [];
    
                // Iterate through the filtered data and create a new structure
                foreach ($filteredData as $item) {
                    $formattedServices['Service' . $serviceCounter] = [
                        'CustomerNo' => $item['CustomerNo'],
                        'Name' => $item['Name'],
                        'ServiceType' => $item['ServiceType'],
                        'ServiceInfo' => $item['ServiceInfo'],
                        'CustomerSite' => $item['CustomerSite'],
                        'Status' => $item['Status'],
                    ];
                    $serviceCounter++; // Increment the counter for the next service
                }
    
                // Log the final enabled items
                \Log::info('Formatted Enabled Services:', $formattedServices);
    
                // Return the formatted services as individual objects
                return response()->json($formattedServices, 200);
            }
    
            return response()->json(['error' => 'Failed to fetch references from API', 'response' => $response->body()], 500);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => 'Validation Error', 'details' => $e->errors()], 422);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(['error' => 'API request timed out or connection failed', 'details' => $e->getMessage()], 504);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred', 'details' => $e->getMessage()], 500);
        }
    }
    

    
    
    
}
