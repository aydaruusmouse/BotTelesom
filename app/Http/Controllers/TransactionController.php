<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller

{

    // New Fiber Installation

    public function showFiberInstallationForm()
    {
        return view('fiber_installation_form'); // Blade template for the form
    }

    public function newFiberInstallation(Request $request)
    {
        $request->validate([
            'callsub' => 'required|string',
            'price' => 'required|numeric',
            'paymentMethod' => 'required|string',
            'contactNumber' => 'required|string',
            'Address' => 'required|string',
            'Center' => 'required|string',
            'Discount' => 'required|numeric',
            'Speed' => 'required|string',
            'TranType' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'apiTokenUser' => 'mob#!Billing!*',
                'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
            ])->post('http://10.10.0.7:8077/api/KaaliyeApi/NewFiberInstallation', [
                'callsub' => $request->input('callsub'),
                'price' => $request->input('price'),
                'paymentMethod' => $request->input('paymentMethod'),
                'contactNumber' => $request->input('contactNumber'),
                'Address' => $request->input('Address'),
                'Center' => $request->input('Center'),
                'Discount' => $request->input('Discount'),
                'Speed' => $request->input('Speed'),
                'TranType' => $request->input('TranType'),
                'description' => $request->input('description'),
            ]);
            

            return response()->json([
                'status' => $response->json('status'),
                'message' => $response->json('Message'),
                'data' => $response->json('Data'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in fiber installation: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing the fiber installation.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    
    public function showBlockTransactionForm()
    {
        return view('block_transaction_form'); // Blade template for form submission
    }

    public function blockWrongTransaction(Request $request)
    {
        $request->validate([
            'msisdn' => 'required|string',
            'transactionnumber' => 'required|string',
            'wrongnumber' => 'required|string',
            'currency_code' => 'required|string',
        ]);

        $msisdn = $request->input('msisdn');
        $transactionNumber = $request->input('transactionnumber');
        $wrongNumber = $request->input('wrongnumber');
        $currencyCode = $request->input('currency_code');

        try {
            $response = Http::withHeaders([
                'apiTokenUser' => 'mob#!Billing!*',
                'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
                'Content-Type' => 'application/json',
            ])->post('http://10.10.0.7:8077/api/KaaliyeApi/BlockWrongTransaction', [
                'msisdn' => $msisdn,
                'transactionnumber' => $transactionNumber,
                'wrongnumber' => $wrongNumber,
                'currency_code' => $currencyCode,
            ]);

            return response()->json([
                'status' => $response->json('status'),
                'message' => $response->json('Message'),
                'data' => $response->json('Data'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in blocking transaction: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing the transaction.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
