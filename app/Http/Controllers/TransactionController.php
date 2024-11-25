<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller

{

    // New Fiber Installation

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

    // Clean callsub and contactNumber
    $callsub = $request->input('callsub');
    $contactNumber = $request->input('contactNumber');

    // Remove "whatsapp:" prefix, "+" signs, and non-digit characters
    $callsub = str_replace(['whatsapp:', '+'], '', $callsub);
    $callsub = preg_replace('/[^0-9]/', '', $callsub);

    $contactNumber = str_replace(['whatsapp:', '+'], '', $contactNumber);
    $contactNumber = preg_replace('/[^0-9]/', '', $contactNumber);

    // Log the cleaned values for debugging purposes
    \Log::info('Processed callsub and contactNumber:', [
        'callsub' => $callsub,
        'contactNumber' => $contactNumber,
    ]);

    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'apiTokenUser' => 'mob#!Billing!*',
            'apiTokenPwd' => 'De6$A7#ES282S@m@l!n.2BIoz',
        ])->post('http://10.10.0.7:8077/api/KaaliyeApi/NewFiberInstallation', [
            'callsub' => $callsub,
            'price' => $request->input('price'),
            'paymentMethod' => $request->input('paymentMethod'),
            'contactNumber' => $contactNumber,
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

    $transactionNumber = $request->input('transactionnumber');
    $wrongNumber = $request->input('wrongnumber');
    $currencyCode = $request->input('currency_code');
    $msisdn = $request->input('msisdn');

    // Clean the msisdn by removing "whatsapp:" prefix and any extra spaces
    $msisdn = str_replace('whatsapp:', '', $msisdn);
    $msisdn = str_replace('+', '', $msisdn);
    $msisdn = preg_replace('/^\D/', '', $msisdn);
    $msisdn = preg_replace('/[^0-9]/', '', $msisdn);
    $msisdn = trim($msisdn);

    // Log the cleaned msisdn for debugging purposes
    \Log::info('Processed MSISDN:', ['msisdn' => $msisdn]);

    // Extract only "Dollar" or "Shilling" from the currency_code
    if (str_contains($currencyCode, 'Zaad')) {
        $currencyCode = str_replace('Zaad', '', $currencyCode); // Remove "Zaad" prefix
        $currencyCode = trim($currencyCode); // Remove any extra spaces
    }

    \Log::info('Processed Currency Code:', ['currency_code' => $currencyCode]);

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
