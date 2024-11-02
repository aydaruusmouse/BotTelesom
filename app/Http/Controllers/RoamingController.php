<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoamingController extends Controller
{
    // Web route to show the form
    public function showActivateRoamingForm()
    {
        return view('activate_roaming_form'); // blade template for the web form
    }

    // API route to handle POST request
    public function activateRoaming(Request $request)
    {
        $request->validate([
            'Callsub' => 'required|string',
            'UserId' => 'required|string',
        ]);

        $callsub = $request->input('Callsub');
        $userId = $request->input('UserId');

        try {
            $response = Http::withHeaders([
                'apiTokenUser' => 'CRMUser',
                'apiTokenPwd' => 'ZEWOALJNADSLLAIE321@!',
                'Content-Type' => 'application/json',
            ])->timeout(20)
            ->post('http://10.55.1.143:8983/api/CRMApi/ActivateRoaming', [
                'Callsub' => $callsub,
                'UserId' => $userId,
            ]);

            return response()->json([
                'status' => $response->json('status'),
                'message' => $response->json('Message'),
                'data' => $response->json('Data'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error activating roaming: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while activating roaming.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
