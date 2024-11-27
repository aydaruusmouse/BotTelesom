<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    /**
     * Show the form for sending OTP.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('otp.form');
    }

    /**
     * Send OTP to the given phone number.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendOtp(Request $request)
    {
        // Validate phone number
        $request->validate([
            'phone_number' => 'required|numeric|digits_between:10,15',
        ]);

        // Generate random OTP
        $otp = rand(100000, 999999);

        // Get phone number from request
        $phoneNumber = $request->input('phone_number');

        // Send OTP via SMS
        $url = "http://172.16.53.115:13013/cgi-bin/sendsms";
        $response = Http::get($url, [
            'to' => '+'.$phoneNumber,
            'text' => 'OTP-'.$otp,
            'username' => 'tester',
            'charset' => 'utf-8',
            'from' => 'OTP',
            'password' => 'foobar',
        ]);

        // Return a JSON response with the OTP and success message
        return response()->json([
            'status' => 'OTP sent successfully!',
            'otp' => $otp,
            'phone_number' => $phoneNumber
        ], 200);
    }
}
