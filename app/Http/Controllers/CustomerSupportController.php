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
        $callsub = $request->input('callsub');
        $problemType = $request->input('problem_type'); // 'Internet' or 'Line'

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
                return response()->json(['error' => 'Unexpected API response structure', 'response' => $decodedResponse], 500);
            }

            // Return the raw data before filtering
            $rawData = $data;

            // Filter based on problem type
            $filteredData = collect($data)->filter(function ($item) use ($problemType) {
                if ($problemType === 'Internet') {
                    return in_array($item['ServiceType'], ['DSL', 'FIBER', 'P2P']);
                } elseif ($problemType === 'Line') {
                    return $item['ServiceType'] === 'DSL';
                }
                return false;
            });

            // Return both raw and filtered data as JSON
            return response()->json([
                'raw_data' => $rawData, // Raw data before filtering
                'filtered_data' => $filteredData->values(), // Filtered data
            ], 200);
        }

        return response()->json(['error' => 'Failed to fetch references from API', 'response' => $response->body()], 500);
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        return response()->json(['error' => 'API request timed out or connection failed', 'details' => $e->getMessage()], 504);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An unexpected error occurred', 'details' => $e->getMessage()], 500);
    }
}

    
    
    
}